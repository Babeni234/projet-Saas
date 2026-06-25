<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\AccountingCategory;
use App\Models\BankAccount;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $accounts = BankAccount::where('user_id', $userId)->orderBy('name')->get();
        $totalBalance = $accounts->sum('balance');

        $recentTransactions = Transaction::where('user_id', $userId)
            ->with(['bankAccount', 'category'])
            ->latest('transaction_date')
            ->take(20)
            ->get();

        $month = now()->month;
        $year = now()->year;

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->sum('amount');

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->sum('amount');

        $pendingCount = Transaction::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $unreconciledCount = Transaction::where('user_id', $userId)
            ->where('is_reconciled', false)
            ->where('status', 'completed')
            ->count();

        // Income/expense trend (last 6 months)
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $trend[] = [
                'month' => $d->format('M'),
                'income' => (float) Transaction::where('user_id', $userId)
                    ->where('type', 'income')->where('status', 'completed')
                    ->whereYear('transaction_date', $d->year)->whereMonth('transaction_date', $d->month)
                    ->sum('amount'),
                'expense' => (float) Transaction::where('user_id', $userId)
                    ->where('type', 'expense')->where('status', 'completed')
                    ->whereYear('transaction_date', $d->year)->whereMonth('transaction_date', $d->month)
                    ->sum('amount'),
            ];
        }

        // Budget progress
        $budgets = Budget::where('user_id', $userId)
            ->where('year', $year)
            ->where('month', $month)
            ->with('category')
            ->get();

        return Inertia::render('Landlord/Pro/Accounting/Index', [
            'accounts' => $accounts,
            'total_balance' => (float) $totalBalance,
            'recent_transactions' => $recentTransactions,
            'monthly_income' => (float) $monthlyIncome,
            'monthly_expense' => (float) $monthlyExpense,
            'pending_count' => $pendingCount,
            'unreconciled_count' => $unreconciledCount,
            'trend' => $trend,
            'budgets' => $budgets,
        ]);
    }

    // ── Bank Accounts ──

    public function accounts()
    {
        $accounts = BankAccount::where('user_id', auth()->id())
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        return Inertia::render('Landlord/Pro/Accounting/Accounts', [
            'accounts' => $accounts,
            'types' => BankAccount::TYPES,
        ]);
    }

    public function storeAccount(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',
            'type' => 'required|string|in:' . implode(',', array_keys(BankAccount::TYPES)),
            'balance' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
        ]);

        $data['user_id'] = auth()->id();
        $data['balance'] = $data['balance'] ?? 0;
        $data['color'] = $data['color'] ?? '#6366f1';

        BankAccount::create($data);

        return redirect()->route('landlord.pro.accounting.accounts')
            ->with('success', 'Compte bancaire créé.');
    }

    public function updateAccount(Request $request, BankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',
            'type' => 'required|string|in:' . implode(',', array_keys(BankAccount::TYPES)),
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $bankAccount->update($data);

        return redirect()->route('landlord.pro.accounting.accounts')
            ->with('success', 'Compte mis à jour.');
    }

    public function destroyAccount(BankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== auth()->id()) abort(403);
        if ($bankAccount->transactions()->exists()) {
            return back()->with('error', 'Supprimez d\'abord les transactions liées.');
        }
        $bankAccount->delete();
        return redirect()->route('landlord.pro.accounting.accounts')
            ->with('success', 'Compte supprimé.');
    }

    // ── Transactions ──

    public function transactions(Request $request)
    {
        $userId = auth()->id();
        $query = Transaction::where('user_id', $userId)
            ->with(['bankAccount', 'category', 'property', 'tenant']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bank_account_id')) {
            $query->where('bank_account_id', $request->bank_account_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                  ->orWhere('reference', 'like', "%{$s}%");
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(25);

        return Inertia::render('Landlord/Pro/Accounting/Transactions', [
            'transactions' => $transactions,
            'accounts' => BankAccount::where('user_id', $userId)->orderBy('name')->get(),
            'categories' => AccountingCategory::where('user_id', $userId)->orderBy('name')->get(),
            'filters' => $request->only(['type', 'status', 'bank_account_id', 'category_id', 'date_from', 'date_to', 'search']),
            'types' => Transaction::TYPES,
            'statuses' => Transaction::STATUSES,
            'payment_methods' => Transaction::PAYMENT_METHODS,
        ]);
    }

    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'category_id' => 'nullable|exists:accounting_categories,id',
            'type' => 'required|string|in:' . implode(',', array_keys(Transaction::TYPES)),
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'status' => 'required|string|in:' . implode(',', array_keys(Transaction::STATUSES)),
            'reference' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|in:' . implode(',', array_keys(Transaction::PAYMENT_METHODS)),
            'property_id' => 'nullable|exists:properties,id',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        $data['user_id'] = auth()->id();

        $txn = Transaction::create($data);

        // Update bank account balance
        $account = BankAccount::find($data['bank_account_id']);
        if ($account && $data['status'] === 'completed') {
            $sign = $data['type'] === 'income' ? 1 : ($data['type'] === 'expense' ? -1 : 0);
            $account->increment('balance', $sign * $data['amount']);
        }

        return redirect()->route('landlord.pro.accounting.transactions')
            ->with('success', 'Transaction enregistrée.');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        // Reverse balance
        if ($transaction->status === 'completed') {
            $account = BankAccount::find($transaction->bank_account_id);
            if ($account) {
                $sign = $transaction->type === 'income' ? -1 : ($transaction->type === 'expense' ? 1 : 0);
                $account->increment('balance', $sign * $transaction->amount);
            }
        }

        $transaction->delete();

        return redirect()->route('landlord.pro.accounting.transactions')
            ->with('success', 'Transaction supprimée.');
    }

    // ── Categories ──

    public function categories()
    {
        $categories = AccountingCategory::where('user_id', auth()->id())
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return Inertia::render('Landlord/Pro/Accounting/Categories', [
            'categories' => $categories,
            'types' => AccountingCategory::TYPES,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(AccountingCategory::TYPES)),
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $data['user_id'] = auth()->id();
        $data['color'] = $data['color'] ?? '#6366f1';

        AccountingCategory::create($data);

        return redirect()->route('landlord.pro.accounting.categories')
            ->with('success', 'Catégorie créée.');
    }

    public function updateCategory(Request $request, AccountingCategory $accountingCategory)
    {
        if ($accountingCategory->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(AccountingCategory::TYPES)),
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $accountingCategory->update($data);

        return redirect()->route('landlord.pro.accounting.categories')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroyCategory(AccountingCategory $accountingCategory)
    {
        if ($accountingCategory->user_id !== auth()->id()) abort(403);
        $accountingCategory->delete();

        return redirect()->route('landlord.pro.accounting.categories')
            ->with('success', 'Catégorie supprimée.');
    }

    // ── Budgets ──

    public function budgets(Request $request)
    {
        $userId = auth()->id();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $budgets = Budget::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->with('category')
            ->get();

        $categories = AccountingCategory::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Landlord/Pro/Accounting/Budgets', [
            'budgets' => $budgets,
            'categories' => $categories,
            'month' => (int) $month,
            'year' => (int) $year,
        ]);
    }

    public function storeBudget(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:accounting_categories,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['user_id'] = auth()->id();

        Budget::updateOrCreate(
            ['user_id' => $data['user_id'], 'category_id' => $data['category_id'], 'month' => $data['month'], 'year' => $data['year']],
            ['amount' => $data['amount']]
        );

        return redirect()->route('landlord.pro.accounting.budgets', ['month' => $data['month'], 'year' => $data['year']])
            ->with('success', 'Budget enregistré.');
    }

    public function destroyBudget(Budget $budget)
    {
        if ($budget->user_id !== auth()->id()) abort(403);
        $budget->delete();

        return back()->with('success', 'Budget supprimé.');
    }
}

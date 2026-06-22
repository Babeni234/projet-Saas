<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

const props = defineProps({
    countries: {
        type: Array,
        default: () => [],
    },
});

// Inject active theme
const theme = inject('theme');

// Search query for the registered countries table
const tableSearchQuery = ref('');

const filteredCountries = computed(() => {
    return props.countries.filter(c => 
        c.name.toLowerCase().includes(tableSearchQuery.value.toLowerCase()) ||
        c.code.toLowerCase().includes(tableSearchQuery.value.toLowerCase())
    );
});

// Search query for the select dropdown
const dropdownSearch = ref('');
const showDropdown = ref(false);

const form = useForm({
    name: '',
    code: '',
    latitude: '',
    longitude: '',
});

// Comprehensive list of countries with coordinates
const worldCountries = [
    { name: "Afghanistan", code: "AF", lat: 33.93911, lng: 67.709953 },
    { name: "Afrique du Sud", code: "ZA", lat: -30.559482, lng: 22.937506 },
    { name: "Albanie", code: "AL", lat: 41.153332, lng: 20.168331 },
    { name: "Algérie", code: "DZ", lat: 28.033886, lng: 1.659626 },
    { name: "Allemagne", code: "DE", lat: 51.165691, lng: 10.451526 },
    { name: "Andorre", code: "AD", lat: 42.506285, lng: 1.521801 },
    { name: "Angola", code: "AO", lat: -11.202692, lng: 17.873887 },
    { name: "Antigua-et-Barbuda", code: "AG", lat: 17.060816, lng: -61.796428 },
    { name: "Arabie Saoudite", code: "SA", lat: 23.885942, lng: 45.079162 },
    { name: "Argentine", code: "AR", lat: -38.416097, lng: -63.616672 },
    { name: "Arménie", code: "AM", lat: 40.069099, lng: 45.038189 },
    { name: "Australie", code: "AU", lat: -25.274398, lng: 133.775136 },
    { name: "Autriche", code: "AT", lat: 47.516231, lng: 14.5501 },
    { name: "Azerbaïdjan", code: "AZ", lat: 40.1431, lng: 47.5769 },
    { name: "Bahamas", code: "BS", lat: 25.0343, lng: -77.3963 },
    { name: "Bahreïn", code: "BH", lat: 25.9304, lng: 50.6377 },
    { name: "Bangladesh", code: "BD", lat: 23.685, lng: 90.3563 },
    { name: "Barbade", code: "BB", lat: 13.1939, lng: -59.5432 },
    { name: "Belgique", code: "BE", lat: 50.503887, lng: 4.469936 },
    { name: "Belize", code: "BZ", lat: 17.1899, lng: -88.4976 },
    { name: "Bénin", code: "BJ", lat: 9.30769, lng: 2.315834 },
    { name: "Bhoutan", code: "BT", lat: 27.5142, lng: 90.4336 },
    { name: "Biélorussie", code: "BY", lat: 53.7098, lng: 27.9534 },
    { name: "Birmanie", code: "MM", lat: 21.9162, lng: 95.956 },
    { name: "Bolivie", code: "BO", lat: -16.2902, lng: -63.5887 },
    { name: "Bosnie-Herzégovine", code: "BA", lat: 43.9159, lng: 17.6791 },
    { name: "Botswana", code: "BW", lat: -22.3285, lng: 24.6849 },
    { name: "Brésil", code: "BR", lat: -14.235, lng: -51.9253 },
    { name: "Brunei", code: "BN", lat: 4.5353, lng: 114.7277 },
    { name: "Bulgarie", code: "BG", lat: 42.7339, lng: 25.4858 },
    { name: "Burkina Faso", code: "BF", lat: 12.238333, lng: -1.561593 },
    { name: "Burundi", code: "BI", lat: -3.373056, lng: 29.918886 },
    { name: "Cambodge", code: "KH", lat: 12.5657, lng: 104.991 },
    { name: "Cameroun", code: "CM", lat: 7.369722, lng: 12.354722 },
    { name: "Canada", code: "CA", lat: 56.130366, lng: -106.346771 },
    { name: "Cap-Vert", code: "CV", lat: 16.0022, lng: -24.0132 },
    { name: "Chili", code: "CL", lat: -35.6751, lng: -71.543 },
    { name: "Chine", code: "CN", lat: 35.8617, lng: 104.1954 },
    { name: "Chypre", code: "CY", lat: 35.1264, lng: 33.4299 },
    { name: "Colombie", code: "CO", lat: 4.5709, lng: -74.2973 },
    { name: "Comores", code: "KM", lat: -11.875, lng: 43.8722 },
    { name: "Congo-Brazzaville", code: "CG", lat: -0.228021, lng: 15.827659 },
    { name: "Congo-Kinshasa (RDC)", code: "CD", lat: -4.038333, lng: 21.758664 },
    { name: "Corée du Nord", code: "KP", lat: 40.3399, lng: 127.5101 },
    { name: "Corée du Sud", code: "KR", lat: 35.9078, lng: 127.7669 },
    { name: "Costa Rica", code: "CR", lat: 9.7489, lng: -83.7534 },
    { name: "Côte d'Ivoire", code: "CI", lat: 7.539989, lng: -5.54708 },
    { name: "Croatie", code: "HR", lat: 45.1, lng: 15.2 },
    { name: "Cuba", code: "CU", lat: 21.5218, lng: -77.7812 },
    { name: "Danemark", code: "DK", lat: 56.2639, lng: 9.5018 },
    { name: "Djibouti", code: "DJ", lat: 11.825138, lng: 42.590275 },
    { name: "Dominique", code: "DM", lat: 15.415, lng: -61.371 },
    { name: "Égypte", code: "EG", lat: 26.8206, lng: 30.8025 },
    { name: "Émirats Arabes Unis", code: "AE", lat: 23.4241, lng: 53.8478 },
    { name: "Équateur", code: "EC", lat: -1.8312, lng: -78.1834 },
    { name: "Érythrée", code: "ER", lat: 15.1794, lng: 39.7823 },
    { name: "Espagne", code: "ES", lat: 40.4637, lng: -3.7492 },
    { name: "Estonie", code: "EE", lat: 58.5953, lng: 25.0136 },
    { name: "États-Unis", code: "US", lat: 37.0902, lng: -95.7129 },
    { name: "Éthiopie", code: "ET", lat: 9.145, lng: 40.4897 },
    { name: "Fidji", code: "FJ", lat: -16.5782, lng: 179.414 },
    { name: "Finlande", code: "FI", lat: 61.9241, lng: 25.7482 },
    { name: "France", code: "FR", lat: 46.2276, lng: 2.2137 },
    { name: "Gabon", code: "GA", lat: -0.803689, lng: 11.609444 },
    { name: "Gambie", code: "GM", lat: 13.443182, lng: -15.310139 },
    { name: "Géorgie", code: "GE", lat: 42.3154, lng: 43.3569 },
    { name: "Ghana", code: "GH", lat: 7.9465, lng: -1.0232 },
    { name: "Grèce", code: "GR", lat: 39.0742, lng: 21.8243 },
    { name: "Grenade", code: "GD", lat: 12.1165, lng: -61.679 },
    { name: "Guatemala", code: "GT", lat: 15.7835, lng: -90.2308 },
    { name: "Guinée", code: "GN", lat: 9.945587, lng: -9.696645 },
    { name: "Guinée équatoriale", code: "GQ", lat: 1.650801, lng: 10.267895 },
    { name: "Guinée-Bissau", code: "GW", lat: 11.803749, lng: -15.180413 },
    { name: "Guyana", code: "GY", lat: 4.8604, lng: -58.9302 },
    { name: "Haïti", code: "HT", lat: 18.9712, lng: -72.6814 },
    { name: "Honduras", code: "HN", lat: 15.2, lng: -86.2419 },
    { name: "Hongrie", code: "HU", lat: 47.1625, lng: 19.5033 },
    { name: "Inde", code: "IN", lat: 20.5937, lng: 78.9629 },
    { name: "Indonésie", code: "ID", lat: -0.7893, lng: 113.9213 },
    { name: "Irak", code: "IQ", lat: 33.2232, lng: 43.6793 },
    { name: "Iran", code: "IR", lat: 32.4279, lng: 53.688 },
    { name: "Irlande", code: "IE", lat: 53.4129, lng: -8.2439 },
    { name: "Islande", code: "IS", lat: 64.9631, lng: -19.0208 },
    { name: "Israël", code: "IL", lat: 31.0461, lng: 34.8516 },
    { name: "Italie", code: "IT", lat: 41.8719, lng: 12.5674 },
    { name: "Jamaïque", code: "JM", lat: 18.1096, lng: -77.2975 },
    { name: "Japon", code: "JP", lat: 36.2048, lng: 138.2529 },
    { name: "Jordanie", code: "JO", lat: 30.5852, lng: 36.2384 },
    { name: "Kazakhstan", code: "KZ", lat: 48.0196, lng: 66.9237 },
    { name: "Kenya", code: "KE", lat: -0.0236, lng: 37.9062 },
    { name: "Kirghizistan", code: "KG", lat: 41.2044, lng: 74.7661 },
    { name: "Kiribati", code: "KI", lat: -3.3704, lng: -168.734 },
    { name: "Kosovo", code: "XK", lat: 42.6026, lng: 20.903 },
    { name: "Koweït", code: "KW", lat: 29.3117, lng: 47.4818 },
    { name: "Laos", code: "LA", lat: 19.8563, lng: 102.4955 },
    { name: "Lesotho", code: "LS", lat: -29.61, lng: 28.2336 },
    { name: "Lettonie", code: "LV", lat: 56.8796, lng: 24.6032 },
    { name: "Liban", code: "LB", lat: 33.8547, lng: 35.8623 },
    { name: "Libéria", code: "LR", lat: 6.428055, lng: -9.429499 },
    { name: "Libye", code: "LY", lat: 26.3351, lng: 17.2283 },
    { name: "Liechtenstein", code: "LI", lat: 47.166, lng: 9.5554 },
    { name: "Lituanie", code: "LT", lat: 55.1694, lng: 23.8813 },
    { name: "Luxembourg", code: "LU", lat: 49.8153, lng: 6.1296 },
    { name: "Macédoine du Nord", code: "MK", lat: 41.6086, lng: 21.7453 },
    { name: "Madagascar", code: "MG", lat: -18.766947, lng: 46.869107 },
    { name: "Malaisie", code: "MY", lat: 4.2105, lng: 101.9758 },
    { name: "Malawi", code: "MW", lat: -13.254308, lng: 34.301525 },
    { name: "Maldives", code: "MV", lat: 3.2028, lng: 73.2207 },
    { name: "Mali", code: "ML", lat: 17.570692, lng: -3.996166 },
    { name: "Malte", code: "MT", lat: 35.9375, lng: 14.3754 },
    { name: "Maroc", code: "MA", lat: 31.7917, lng: -7.0926 },
    { name: "Maurice", code: "MU", lat: -20.3484, lng: 57.5522 },
    { name: "Mauritanie", code: "MR", lat: 21.00789, lng: -10.940835 },
    { name: "Mexique", code: "MX", lat: 23.6345, lng: -102.5528 },
    { name: "Micronésie", code: "FM", lat: 7.4256, lng: 150.5508 },
    { name: "Moldavie", code: "MD", lat: 47.4116, lng: 28.3699 },
    { name: "Monaco", code: "MC", lat: 43.7384, lng: 7.4246 },
    { name: "Mongolie", code: "MN", lat: 46.8625, lng: 103.8467 },
    { name: "Monténégro", code: "ME", lat: 42.7087, lng: 19.3744 },
    { name: "Mozambique", code: "MZ", lat: -18.665695, lng: 35.529562 },
    { name: "Namibie", code: "NA", lat: -22.9576, lng: 18.4904 },
    { name: "Nauru", code: "NR", lat: -0.5228, lng: 166.9315 },
    { name: "Népal", code: "NP", lat: 28.3949, lng: 84.124 },
    { name: "Nicaragua", code: "NI", lat: 12.8654, lng: -85.2072 },
    { name: "Niger", code: "NE", lat: 17.607789, lng: 8.081666 },
    { name: "Nigeria", code: "NG", lat: 9.082012, lng: 8.675277 },
    { name: "Norvège", code: "NO", lat: 60.472, lng: 8.4689 },
    { name: "Nouvelle-Zélande", code: "NZ", lat: -40.9006, lng: 174.886 },
    { name: "Oman", code: "OM", lat: 21.5126, lng: 55.9233 },
    { name: "Ouganda", code: "UG", lat: 1.373333, lng: 32.290275 },
    { name: "Ouzbékistan", code: "UZ", lat: 41.3775, lng: 64.5853 },
    { name: "Pakistan", code: "PK", lat: 30.3753, lng: 69.3451 },
    { name: "Palaos", code: "PW", lat: 7.515, lng: 134.5825 },
    { name: "Palestine", code: "PS", lat: 31.9522, lng: 35.2332 },
    { name: "Panama", code: "PA", lat: 8.538, lng: -80.7821 },
    { name: "Papouasie-Nouvelle-Guinée", code: "PG", lat: -6.315, lng: 143.9555 },
    { name: "Paraguay", code: "PY", lat: -23.4425, lng: -58.4438 },
    { name: "Pays-Bas", code: "NL", lat: 52.1326, lng: 5.2913 },
    { name: "Pérou", code: "PE", lat: -9.19, lng: -75.0152 },
    { name: "Philippines", code: "PH", lat: 12.8797, lng: 121.774 },
    { name: "Pologne", code: "PL", lat: 51.9194, lng: 19.1451 },
    { name: "Portugal", code: "PT", lat: 39.3999, lng: -8.2245 },
    { name: "Qatar", code: "QA", lat: 25.3548, lng: 51.1839 },
    { name: "République Centrafricaine", code: "CF", lat: 6.611111, lng: 20.939444 },
    { name: "République Dominicaine", code: "DO", lat: 18.7357, lng: -70.1627 },
    { name: "République Tchèque", code: "CZ", lat: 49.8175, lng: 15.473 },
    { name: "Roumanie", code: "RO", lat: 45.9432, lng: 24.9668 },
    { name: "Royaume-Uni", code: "GB", lat: 55.3781, lng: -3.436 },
    { name: "Russie", code: "RU", lat: 61.524, lng: 105.3188 },
    { name: "Rwanda", code: "RW", lat: -1.940278, lng: 29.873887 },
    { name: "Saint-Christophe-et-Niévès", code: "KN", lat: 17.3578, lng: -62.783 },
    { name: "Saint-Marin", code: "SM", lat: 43.9424, lng: 12.4578 },
    { name: "Saint-Vincent-et-les-Grenadines", code: "VC", lat: 12.9843, lng: -61.2872 },
    { name: "Sainte-Lucie", code: "LC", lat: 13.9094, lng: -60.9789 },
    { name: "Salomon", code: "SB", lat: -9.6457, lng: 160.1562 },
    { name: "Samoa", code: "WS", lat: -13.759, lng: -172.1046 },
    { name: "Sao Tomé-et-Principe", code: "ST", lat: 0.18636, lng: 6.613081 },
    { name: "Sénégal", code: "SN", lat: 14.497401, lng: -14.452362 },
    { name: "Serbie", code: "RS", lat: 44.0165, lng: 21.0059 },
    { name: "Seychelles", code: "SC", lat: -4.6796, lng: 55.492 },
    { name: "Sierra Leone", code: "SL", lat: 8.460555, lng: -11.779889 },
    { name: "Singapour", code: "SG", lat: 1.3521, lng: 103.8198 },
    { name: "Slovaquie", code: "SK", lat: 48.669, lng: 19.699 },
    { name: "Slovénie", code: "SI", lat: 46.1512, lng: 14.9955 },
    { name: "Somalie", code: "SO", lat: 5.152149, lng: 46.199616 },
    { name: "Soudan", code: "SD", lat: 12.8628, lng: 30.2176 },
    { name: "Soudan du Sud", code: "SS", lat: 6.877, lng: 31.307 },
    { name: "Sri Lanka", code: "LK", lat: 7.8731, lng: 80.7718 },
    { name: "Suède", code: "SE", lat: 60.1282, lng: 18.6435 },
    { name: "Suisse", code: "CH", lat: 46.8182, lng: 8.2275 },
    { name: "Suriname", code: "SR", lat: 3.9193, lng: -56.0278 },
    { name: "Eswatini (Swaziland)", code: "SZ", lat: -26.5225, lng: 31.4659 },
    { name: "Syrie", code: "SY", lat: 34.8021, lng: 38.9968 },
    { name: "Tadjikistan", code: "TJ", lat: 38.861, lng: 71.2761 },
    { name: "Taïwan", code: "TW", lat: 23.6978, lng: 120.9605 },
    { name: "Tanzanie", code: "TZ", lat: -6.369028, lng: 34.888822 },
    { name: "Tchad", code: "TD", lat: 15.454166, lng: 18.732207 },
    { name: "Thaïlande", code: "TH", lat: 15.87, lng: 100.9925 },
    { name: "Timor oriental", code: "TL", lat: -8.8742, lng: 125.7275 },
    { name: "Togo", code: "TG", lat: 8.619543, lng: 0.824782 },
    { name: "Tonga", code: "TO", lat: -21.1789, lng: -175.1982 },
    { name: "Trinité-et-Tobago", code: "TT", lat: 10.6918, lng: -61.2225 },
    { name: "Tunisie", code: "TN", lat: 33.8869, lng: 9.5375 },
    { name: "Turkménistan", code: "TM", lat: 38.9697, lng: 59.5563 },
    { name: "Turquie", code: "TR", lat: 38.9637, lng: 35.2433 },
    { name: "Tuvalu", code: "TV", lat: -7.1095, lng: 177.6493 },
    { name: "Ukraine", code: "UA", lat: 48.3794, lng: 31.1656 },
    { name: "Uruguay", code: "UY", lat: -32.5228, lng: -55.7658 },
    { name: "Vanuatu", code: "VU", lat: -15.3767, lng: 166.9592 },
    { name: "Vatican", code: "VA", lat: 41.9029, lng: 12.4534 },
    { name: "Venezuela", code: "VE", lat: 6.4238, lng: -66.5897 },
    { name: "Viêt Nam", code: "VN", lat: 14.0583, lng: 108.2772 },
    { name: "Yémen", code: "YE", lat: 15.5527, lng: 48.5164 },
    { name: "Zambie", code: "ZM", lat: -13.133897, lng: 27.849332 },
    { name: "Zimbabwe", code: "ZW", lat: -19.015438, lng: 29.154857 }
];

// Search/filter the dropdown options
const filteredDropdownCountries = computed(() => {
    if (!dropdownSearch.value) return worldCountries.slice(0, 10); // Show first 10 by default
    return worldCountries.filter(item => 
        item.name.toLowerCase().includes(dropdownSearch.value.toLowerCase()) ||
        item.code.toLowerCase().includes(dropdownSearch.value.toLowerCase())
    );
});

// Select a country from list and populate values
const selectDropdownCountry = (country) => {
    form.name = country.name;
    form.code = country.code;
    form.latitude = country.lat;
    form.longitude = country.lng;
    dropdownSearch.value = country.name;
    showDropdown.value = false;
};

// Form submit
const submit = () => {
    form.post(route('superadmin.countries.store'), {
        onSuccess: () => {
            form.reset();
            dropdownSearch.value = '';
        }
    });
};
</script>

<template>
    <Head title="Gestion des Pays" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Pays Autorisés</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Gérez la liste des pays dans lesquels l'application Property AI est opérationnelle, avec leurs coordonnées de géolocalisation de référence.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Registration Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] space-y-6">
                        <div>
                            <h3 class="text-base font-extrabold text-[var(--text-main)]">Enregistrer un Pays</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Recherchez un pays pour remplir automatiquement ses coordonnées géographiques.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Country Select / Autocomplete Search -->
                            <div class="relative">
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Rechercher le Pays</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                    </span>
                                    <input 
                                        type="text"
                                        v-model="dropdownSearch"
                                        placeholder="Taper pour rechercher..."
                                        @focus="showDropdown = true"
                                        class="w-full pl-10 pr-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="dropdownSearch" @click="dropdownSearch = ''; showDropdown = true;" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 cursor-pointer hover:text-slate-300">
                                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                                    </span>
                                </div>

                                <!-- Floating Custom Select Dropdown -->
                                <div v-if="showDropdown" class="absolute z-50 w-full mt-2 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl shadow-xl max-h-60 overflow-y-auto backdrop-blur-xl">
                                    <div 
                                        v-for="country in filteredDropdownCountries" 
                                        :key="country.code"
                                        @click="selectDropdownCountry(country)"
                                        class="px-4 py-3 text-xs text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-indigo-500/10 cursor-pointer transition-colors border-b border-[var(--border-color)]/20 last:border-b-0 flex items-center justify-between"
                                    >
                                        <span class="font-bold">{{ country.name }}</span>
                                        <span class="text-[10px] bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20 text-indigo-400 font-extrabold">{{ country.code }}</span>
                                    </div>
                                    <div v-if="filteredDropdownCountries.length === 0" class="px-4 py-3 text-xs text-[var(--text-muted)] text-center font-bold">
                                        Aucun pays trouvé.
                                    </div>
                                </div>
                            </div>

                            <hr class="border-[var(--border-color)]/30 my-4" />

                            <!-- Country Name (Readonly or Editable) -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom du Pays</label>
                                <input 
                                    type="text" 
                                    v-model="form.name"
                                    required
                                    placeholder="Ex: Cameroun"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.name }}</span>
                            </div>

                            <!-- Country Code (2 letters) -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Code ISO (2 lettres)</label>
                                <input 
                                    type="text" 
                                    v-model="form.code"
                                    required
                                    maxlength="2"
                                    placeholder="Ex: CM"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] uppercase focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.code" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.code }}</span>
                            </div>

                            <!-- Geographical Coordinates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Latitude</label>
                                    <input 
                                        type="number" 
                                        step="0.000001"
                                        v-model="form.latitude"
                                        required
                                        placeholder="Ex: 7.3697"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="form.errors.latitude" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.latitude }}</span>
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Longitude</label>
                                    <input 
                                        type="number" 
                                        step="0.000001"
                                        v-model="form.longitude"
                                        required
                                        placeholder="Ex: 12.3547"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="form.errors.longitude" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.longitude }}</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/10 active:scale-[0.98] transition-all disabled:opacity-50 mt-6 flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Enregistrer le pays</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Filters/Search Bar -->
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col md:flex-row items-center gap-4 justify-between">
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input 
                                v-model="tableSearchQuery"
                                type="text" 
                                placeholder="Rechercher par nom ou code..."
                                class="w-full pl-10 pr-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all shadow-inner"
                            />
                        </div>
                        <span class="text-xs text-[var(--text-muted)] font-bold">
                            {{ filteredCountries.length }} pays enregistré(s)
                        </span>
                    </div>

                    <!-- Registered countries table -->
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted)] tracking-wider">
                                        <th class="p-6">Drapeau / Code</th>
                                        <th class="p-6">Nom</th>
                                        <th class="p-6">Latitude</th>
                                        <th class="p-6">Longitude</th>
                                        <th class="p-6 text-right">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border-color)] text-xs">
                                    <tr v-for="c in filteredCountries" :key="c.id" class="hover:bg-[var(--bg-table-hover)] transition-colors">
                                        <td class="p-6">
                                            <div class="flex items-center gap-3">
                                                <span class="h-9 w-9 bg-indigo-500/10 text-indigo-400 font-extrabold flex items-center justify-center rounded-xl border border-indigo-500/20 text-xs">
                                                    {{ c.code }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-6 font-extrabold text-sm text-[var(--text-main)]">
                                            {{ c.name }}
                                        </td>
                                        <td class="p-6 text-[var(--text-muted)] font-mono font-bold">
                                            {{ c.latitude.toFixed(6) }}
                                        </td>
                                        <td class="p-6 text-[var(--text-muted)] font-mono font-bold">
                                            {{ c.longitude.toFixed(6) }}
                                        </td>
                                        <td class="p-6 text-right">
                                            <span class="inline-block px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/25">
                                                Actif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredCountries.length === 0">
                                        <td colspan="5" class="p-12 text-center text-[var(--text-muted)] font-bold">Aucun pays enregistré.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>

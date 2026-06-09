<?php

return [

    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),

    'openai_model' => env('OPENAI_MODEL', 'gpt-4o-mini'),

    'groq_model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),

    'verification_timeout' => (int) env('AI_VERIFICATION_TIMEOUT', 90),

];

<x-layouts.app title="Inscription">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <span class="text-3xl">💍</span>
                    <span class="text-2xl font-bold gradient-text">Mariage Planner</span>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Créer un compte</h1>
                <p class="text-gray-500 mt-1">C'est gratuit et prend moins d'une minute</p>
            </div>

            <div class="card">
                <a href="{{ route('social.redirect', 'google') }}"
                   class="btn-outline w-full mb-4 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582L19.91 3C17.782 1.145 15.055 0 12 0 7.27 0 3.198 2.698 1.24 6.65l4.026 3.115Z"/><path fill="#34A853" d="M16.04 18.013c-1.09.703-2.474 1.078-4.04 1.078a7.077 7.077 0 0 1-6.723-4.823l-4.04 3.067A11.965 11.965 0 0 0 12 24c2.933 0 5.735-1.043 7.834-3l-3.793-2.987Z"/><path fill="#4A90E2" d="M19.834 21c2.195-2.048 3.62-5.096 3.62-9 0-.71-.109-1.473-.272-2.182H12v4.637h6.436c-.317 1.559-1.17 2.766-2.395 3.558L19.834 21Z"/><path fill="#FBBC05" d="M5.277 14.268A7.12 7.12 0 0 1 4.909 12c0-.782.125-1.533.357-2.235L1.24 6.65A11.934 11.934 0 0 0 0 12c0 1.92.445 3.73 1.237 5.335l4.04-3.067Z"/></svg>
                    Continuer avec Google
                </a>

                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-400">ou</span></div>
                </div>

                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">Nom complet</label>
                        <input name="name" type="text" required value="{{ old('name') }}"
                               class="input @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input name="email" type="email" required value="{{ old('email') }}"
                               class="input @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Mot de passe</label>
                        <input name="password" type="password" required
                               class="input @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Confirmer le mot de passe</label>
                        <input name="password_confirmation" type="password" required class="input">
                    </div>
                    <label class="flex items-start gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="marketing_consent" class="rounded border-gray-300 text-violet-600 mt-0.5">
                        <span>J'accepte de recevoir des conseils et actualités sur l'organisation de mariage</span>
                    </label>
                    <button type="submit" class="btn-primary w-full">Créer mon compte gratuit</button>
                    <p class="text-center text-xs text-gray-400">
                        En créant un compte, vous acceptez nos
                        <a href="#" class="underline">CGU</a> et notre
                        <a href="#" class="underline">politique de confidentialité</a>.
                    </p>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500 mt-6">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-violet-600 font-medium hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</x-layouts.app>

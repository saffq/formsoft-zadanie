<template>
    <div class="flex flex-col min-h-screen bg-white text-black font-sans">
        <header class="bg-gray-100 shadow py-4">
            <div class="container mx-auto px-4">
                <h1 class="text-3xl font-bold">Formsoft zadanie</h1>
            </div>
        </header>
        <main class="flex-grow container mx-auto py-8 px-4">
            <slot />
        </main>
        <footer class="bg-gray-100 shadow py-4">
            <div class="container mx-auto px-4 text-center">
                © 2025 Krystian Stępień
            </div>
        </footer>
    </div>
</template>


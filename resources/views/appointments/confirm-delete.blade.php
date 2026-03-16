<x-app-layout>
    <div class="py-12 max-w-lg mx-auto px-4">
        <div class="bg-white p-8 rounded-lg shadow-xl border-t-4 border-red-500 text-center">
            <div class="text-red-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Confirmar Exclusão</h2>
            <p class="text-gray-600 mb-6">
                Você tem certeza que deseja excluir o agendamento de <br>
                <span class="font-black text-red-600 text-lg">Sr(a). {{ $appointment->client_name }}</span>?
            </p>

            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="flex space-x-4">
                @csrf
                @method('DELETE')
                <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 shadow-lg">
                    Sim, Excluir
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
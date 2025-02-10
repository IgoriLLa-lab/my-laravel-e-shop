<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div class="col-md-12">
                            <h1>Заказы</h1>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Имя
                                    </th>
                                    <th>
                                        Телефон
                                    </th>
                                    <th>
                                        Когда отправлен
                                    </th>
                                    <th>
                                        Сумма
                                    </th>
                                    <th>
                                        Действия
                                    </th>
                                </tr>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id}}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->created_at->format('H:i d/m/Y') }}</td>
                                        <td>{{ $order->getTotalCost() }} руб.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-success" type="button"
                                                   href="{{ route('orders.show', $order) }}">Открыть</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

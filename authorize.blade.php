@extends('layouts.app')
@section('content')

    <div class="max-w-3xl mx-auto bg-white overflow-hidden shadow rounded-lg">
        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Authorization Request
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p>
                <strong>{{ $client->name }}</strong> 
                is requesting permission to access your account.
            </p>
            <!-- Scope List -->
            @if (count($scopes) > 0)
                <div class="scopes">
                    <p><strong>This application will be able to:</strong></p>
                    <ul>
                        @foreach ($scopes as $scope)
                            <li>{{ $scope->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse border-t border-gray-200 px-4 py-4 sm:px-6">
            <!-- Authorize Button -->
            <form class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto" method="post" action="{{ route('passport.authorizations.approve') }}">
                @csrf
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <span >
                    <button type="submit" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition ease-in-out duration-150">
                        Authorize
                    </button>
                </span>
            </form>

            <!-- Cancel Button -->
            <form class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto" method="post" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <span >
                    <button type="submit" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition ease-in-out duration-150">
                        Cancel
                    </button>
                </span>
            </form>
            <!-- Content goes here -->
            <!-- We use less vertical padding on card footers at all sizes than on headers or body sections -->
        </div>
    </div>
@endsection

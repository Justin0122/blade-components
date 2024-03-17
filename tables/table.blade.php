@props(['results', 'type', 'create' => false, 'fillables' => null, 'crud' => true, 'can' => null])
<div class="mx-4">
    <table class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800 dark:text-gray-200">
        <thead>
        <tr>
            @foreach($fillables ? $fillables : $results[0]->getFillable() as $fillable)
                <th class="px-4 py-2 text-left">{{ ucwords(str_replace('_', ' ', join(' ', preg_split('/(?=[A-Z])/', $fillable)))) }}</th>
            @endforeach
            <th class="px-4 py-2 text-left">Created At</th>
            @if ($crud)
                @if ($can)
                    @can($can)
                        <th class="px-4 py-2 text-left">Actions</th>
                    @endcan
                @endif
            @endif
        </tr>
        </thead>
        <tbody>
        @if ($create)
            @if ($can)
                @can($can)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <form wire:submit.prevent="create">
                            @foreach($fillables ? $fillables : $results[0]->getFillable() as $fillable)
                                <td class="py-2 px-4">
                                    <label for="{{ $results[0]->$fillable ?? $fillable }}"></label>
                                    @if (str_contains($fillable, 'date') !== false)
                                        <x-input
                                                type="date"
                                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                                wire:model="form.{{ $fillable }}"
                                        />
                                        @error('form.' . $fillable) <span
                                                class="text-red-500">{{ str_replace('form.', '', $message) }}</span> @enderror
                                    @elseif (str_contains($fillable, 'image') !== false || str_contains($fillable, 'logo') !== false)
                                        <x-input
                                                type="file"
                                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                                wire:model="form.{{ $fillable }}"
                                        />
                                        @error('form.' . $fillable) <span
                                                class="text-red-500">{{ str_replace('form.', '', $message) }}</span> @enderror
                                    @elseif (str_contains($fillable, 'time') !== false)
                                        <x-input
                                                type="datetime-local"
                                                min="'{{ date('Y-m-d\TH:i:s', strtotime(now())) }}'"
                                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                                wire:model="form.{{ $fillable }}"
                                        />
                                    @else
                                        <x-input
                                                type="text"
                                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                                wire:model="form.{{ $fillable }}"
                                        />
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-2 px-4 grid grid-cols-2 gap-2"></td>
                            <td class="py-2 px-4">
                                <x-button>Add</x-button>
                            </td>
                        </form>
                    </tr>
                @endcan
            @endif
        @endif
        @foreach ($results as $result)
            <tr class="{{ $loop->odd ? 'bg-gray-50 dark:bg-gray-900' : '' }}">
                @foreach ($result->getFillable() as $field)
                    <td class="py-2 px-4">
                        @if (str_contains($field, 'image') !== false || str_contains($field, 'logo') !== false)
                            <x-image :src="$result->$field"/>
                        @else
                            @if (str_contains($field, '_id') !== false)
                                {{ $result->{str_replace('_id', '', $field)}->name }}
                            @else
                                {{ $result->$field }}
                            @endif
                        @endif
                    </td>
                @endforeach
                <td class="py-2 px-4">
                    {{ $result->created_at }} ({{ $result->created_at->diffForHumans() }})
                </td>
                @if ($crud)
                    @if ($can)
                        @can($can)
                            <td class="py-2 px-4">
                                <div class="grid grid-cols-2 gap-2">
                                    <x-button href="{{ route($type . '.edit', $result->id) }}">Edit</x-button>
                                    <x-danger-button wire:click="delete({{ $result->id }})">Delete</x-danger-button>
                                </div>
                            </td>
                        @endcan
                    @endcan
                @endif
            </tr>
        @endforeach

        @if ($results->count() == 0)
            <tr>
                <td colspan="4" class="py-2 text-center">No results found.</td>
            </tr>
        @endif
        </tbody>
    </table>

    <div class="p-4">
        @if ($results->count() > 0)
            {{ $results->links() }}
        @endif
    </div>
</div>

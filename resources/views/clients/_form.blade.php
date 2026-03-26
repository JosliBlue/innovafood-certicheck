@php
    /** @var \App\Models\Client|null $client */
    $isEdit = isset($client);
@endphp

{{-- Cédula --}}
<div class="flex flex-col gap-1">
    <label for="id_card" class="text-xs font-bold text-primary uppercase tracking-wide">Cédula</label>
    <input id="id_card" type="text" name="id_card" value="{{ old('id_card', $client->id_card ?? '') }}"
        class="border {{ $errors->has('id_card') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('id_card')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Apellidos --}}
<div class="flex flex-col gap-1">
    <label for="last_names" class="text-xs font-bold text-primary uppercase tracking-wide">Apellidos</label>
    <input id="last_names" type="text" name="last_names" value="{{ old('last_names', $client->last_names ?? '') }}"
        class="border {{ $errors->has('last_names') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('last_names')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Nombres --}}
<div class="flex flex-col gap-1">
    <label for="first_names" class="text-xs font-bold text-primary uppercase tracking-wide">Nombres</label>
    <input id="first_names" type="text" name="first_names" value="{{ old('first_names', $client->first_names ?? '') }}"
        class="border {{ $errors->has('first_names') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('first_names')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Fecha de nacimiento --}}
<div class="flex flex-col gap-1">
    <label for="birthday" class="text-xs font-bold text-primary uppercase tracking-wide">Fecha de nacimiento</label>
    <input id="birthday" type="date" name="birthday"
        value="{{ old('birthday', isset($client) ? $client->birthday->format('Y-m-d') : '') }}"
        class="border {{ $errors->has('birthday') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('birthday')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Nombre del curso --}}
<div class="flex flex-col gap-1">
    <label for="course_name" class="text-xs font-bold text-primary uppercase tracking-wide">Nombre del curso</label>
    <input id="course_name" type="text" name="course_name" value="{{ old('course_name', $client->course_name ?? '') }}"
        class="border {{ $errors->has('course_name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('course_name')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Tipo de suscripción --}}
<div class="flex flex-col gap-1">
    <label for="subscription_type" class="text-xs font-bold text-primary uppercase tracking-wide">Tipo de
        suscripción</label>
    <input id="subscription_type" type="text" name="subscription_type"
        value="{{ old('subscription_type', $client->subscription_type ?? '') }}"
        class="border {{ $errors->has('subscription_type') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('subscription_type')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>

{{-- Fecha de vencimiento --}}
<div class="flex flex-col gap-1">
    <label for="expires_at" class="text-xs font-bold text-primary uppercase tracking-wide">Fecha de vencimiento</label>
    <input id="expires_at" type="date" name="expires_at"
        value="{{ old('expires_at', isset($client) ? $client->expires_at->format('Y-m-d') : '') }}"
        class="border {{ $errors->has('expires_at') ? 'border-red-400' : 'border-gray-200' }} rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    @error('expires_at')
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>
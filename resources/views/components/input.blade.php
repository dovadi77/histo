<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control" name="{{ $name }}" id="{{ $id }}"
        {{ $type == 'file' ? 'accept=image/*' : '' }} value="{{ $value ?? '' }}" />
    @error($name)
        <small id=" {{ $name }}Error" class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

@extends('layouts.app')
@section('title', 'Add New Product')
@section('content')
<div class="uk-section uk-section-small uk-section-muted uk-flex uk-flex-center">
  <div class="uk-card uk-card-default uk-card-body uk-width-large">
    <h2 class="uk-card-title">Add New Product</h2>
    <form method="POST" enctype="multipart/form-data" action="{{route('process_new_product')}}" class="uk-form-stacked">
      @csrf
      <div class="uk-margin">
        <label for="title" class="uk-form-label">
          {{ __('Title') }}
        </label>
        <div class="uk-form-control">
          <input id="title" class="uk-input @error('title') uk-form-danger @enderror" name="title" required autofocus
            value="{{ old('title') }}">
          @error('title')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <label for="description" class="uk-form-label">
          {{ __('Product description') }}
        </label>
        <div class="uk-form-control">
          <textarea id="description" class="uk-textarea @error('description') uk-form-danger @enderror"
            name="description" required>{{ old('description') }}</textarea>
          @error('description')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <label for="image" class="uk-form-label">
          {{ __('Product Images') }}
        </label>
        <div class="uk-form-control">
          <input name="images[]" placeholder="Select Images" class="uk-input @error('image')  uk-form-danger @enderror"
            id="image" multiple accept=".jpg, .png, .jpeg" type="file" />
          @error('image')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <label for="amount" class="uk-form-label">
          {{ __('Product Amount') }}
        </label>
        <div class="uk-form-control">
          <input name="amount" placeholder="Select Amount" class="uk-input @error('amount')  uk-form-danger @enderror"
            id="amount" type="number" />
          @error('amount')
          <span class="uk-text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="uk-margin">
        <div class="uk-form-control">
          <button type="submit" class="uk-button uk-button-primary">
            {{ __('Create') }}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

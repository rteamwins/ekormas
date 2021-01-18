@extends('layouts.app')
@section('title', 'Add New Product')
@section('content')
<div class="uk-container uk-padding-remove">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">NEW PRODUCT</h2>
          <p class="uk-margin-remove-top">
            Create New Product.
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <form method="POST" enctype="multipart/form-data" action="{{route('process_new_product')}}"
            class="uk-form-stacked">
            @csrf
            <div class="uk-margin">
              <label for="title" class="uk-form-label">
                {{ __('Title') }}
              </label>
              <div class="uk-form-control">
                <input id="title" class="uk-input @error('title') uk-form-danger @enderror" name="title" required
                  autofocus value="{{ old('title') }}">
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
              <label for="product_images" class="uk-form-label">
                {{ __('Product Images') }}
              </label>
              <div class="uk-form-control">
                <input name="product_images[]" placeholder="Select Images"
                  class="uk-input @error('image')  uk-form-danger @enderror" id="product_images" multiple
                  accept=".jpg, .png, .jpeg" type="file" />
                @error('product_images')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin">
              <label for="amount" class="uk-form-label">
                {{ __('Product Amount') }}
              </label>
              <div class="uk-form-control">
                <input name="amount" placeholder="Select Amount"
                  class="uk-input @error('amount')  uk-form-danger @enderror" id="amount" type="number"
                  value="{{ old('amount') }}" />
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
    </div>
  </div>
</div>
@endsection

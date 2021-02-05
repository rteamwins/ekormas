@extends('layouts.app')
@push('scripts_bottom')
<script>
  const axios_config = {
    headers: {
      'content-type': 'application/json',
      'Accept': 'application/json'
    }
  }
function delete_old_image(x,y,z) {
      axios
        .get(
          `${location.origin}/api/product/${x}/delete_image/${y}`, axios_config
        )
        .then(res => {
          // console.log(res.data)
          if(res.data.message =='deleted'){
            var img_itm = document.getElementById(z);
            img_itm.parentNode.removeChild(img_itm);
          }
        })
        .catch(err => {
          const { status } = err.response;
          if (status === 401) {
            console.log(err.response.data);
          } else if (status === 422) {
            console.log(err.response.data);
          } else {
            console.log(err.response);
          }
        });
    }
</script>
@endpush
@section('title', 'Editing '.$product->title)
@section('content')
<div class="uk-container uk-padding-remove">

  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">EDIT PRODUCT</h2>
          <p class="uk-margin-remove-top">
            Editing Product: {{$product->title}}
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <form method="POST" enctype="multipart/form-data" action="{{route('update_product',['id'=>$product->id])}}"
            class="uk-form-stacked">
            @csrf
            <div class="uk-margin">
              <label for="title" class="uk-form-label">
                {{ __('Title') }}
              </label>
              <div class="uk-form-control">
                <input id="title" class="uk-input @error('title') uk-form-danger @enderror" name="title" required
                  autofocus value="{{ old('title')?:$product->title }}">
                @error('title')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-grid-small uk-child-width-1-2" uk-grid>
              <div class="">
                <label for="category" class="uk-form-label">
                  {{ __('Category') }}
                </label>
                <div class="uk-form-control">
                  <select id="category" class="uk-input @error('category') uk-form-danger @enderror" name="category"
                    required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                    <option @if($cat->id == (old('category')?:$product->category_id)) selected @endif
                      value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                  </select>
                  @error('category')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="">
                <label for="amount" class="uk-form-label">
                  {{ __('Product Amount') }}
                </label>
                <div class="uk-form-control">
                  <input name="amount" placeholder="Select Amount"
                    class="uk-input @error('amount')  uk-form-danger @enderror" id="amount" type="number"
                    value="{{ old('amount')?:$product->amount }}" />
                  @error('amount')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="">
                <label for="reward_level" class="uk-form-label">
                  {{ __('Reward Level') }}
                </label>
                <div class="uk-form-control">
                  <input type="number" min="1" max="99" id="reward_level"
                    class="uk-input @error('reward_level') uk-form-danger @enderror" name="reward_level" required
                    value="{{ old('reward_level')?:$product->reward_level }}">
                  @error('reward_level')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="">
                <label for="delivery_duration" class="uk-form-label">
                  {{ __('Delivery Time') }}
                </label>
                <div class="uk-form-control">
                  <input type="number" min="1" max="99" id="delivery_duration"
                    class="uk-input @error('delivery_duration') uk-form-danger @enderror" name="delivery_duration"
                    required value="{{ old('delivery_duration')?:$product->delivery_duration }}">
                  @error('delivery_duration')
                  <span class="uk-text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="uk-margin">
              <label for="description" class="uk-form-label">
                {{ __('Product description') }}
              </label>
              <div class="uk-form-control">
                <textarea id="description" class="uk-textarea @error('description') uk-form-danger @enderror"
                  name="description" required>{{ old('description')?:$product->description }}</textarea>
                @error('description')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin uk-width-1-1">
              <label class="uk-form-label"><b>Old Images</b></label>
              <ul class="uk-thumbnav" uk-margin>
                @foreach ($product->images as $key=> $img)
                <li class="uk-active" id="pkimg_{{$key}}">
                  <img src="{{$img}}" width="150" class=" uk-display-block" style="object-fit:cover;height:150px;"
                    alt="" /><button type="button"
                    onclick="delete_old_image('{{$product->id}}','{{pathinfo($img)['basename']}}','pkimg_{{$key}}')"
                    class="uk-button uk-button-small uk-button-danger uk-width-1-1">
                    Remove</span>
                  </button>
                </li>
                @endforeach
              </ul>
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
              <div class="uk-form-control">
                <button type="submit" class="uk-button uk-button-primary">
                  {{ __('Update') }}
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

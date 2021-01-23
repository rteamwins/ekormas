@push('scripts_bottom')
<script>
  const states = {!! json_encode($states->toArray(), JSON_HEX_TAG) !!};
  const lgas = {!! json_encode($lgas->toArray(), JSON_HEX_TAG) !!};
  const relevant_lga = sel_state_id => {
    return lgas.filter(lga => lga.state_id == sel_state_id)
  }
  const update_lga_list = (elem) =>{
  var intending_lga = document.getElementById('intending_lga')
    intending_lga.length = 1;
    let new_lgas = [...relevant_lga(elem.value)]
    new_lgas.forEach(lga => {
      let new_option = document.createElement('option')
      new_option.text = lga.name
      new_option.value = lga.id
      intending_lga.add(new_option)
    });
  }
</script>
@endpush
@extends('layouts.app')
@section('title', 'Prospective Agent')
@section('content')
<div class="uk-container uk-padding-remove">
  @include('layouts.user_stats_card')
  <div class="uk-margin-large-bottom uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@s uk-width-1-2@m">
      <div class="uk-card uk-card-default uk-border-rounded">
        <div class="uk-card-header">
          <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">PROSPECTIVE AGENT</h2>
          <p class="uk-margin-remove-top">
            Agent Application Form
          </p>
        </div>
        <div class="uk-card-body uk-padding-small">
          <div class="uk-width-1-1 cyan-text text-lighten-1 cyan lighten-4 uk-border-rounded" uk-alert>
            <p>An Application Fee of <b>$10,000</b> is required for the processing of this request.</p>
          </div>
          <form method="POST" enctype="multipart/form-data" action="{{route('agent_application_form_store')}}"
            class="uk-form-stacked">
            @csrf
            <div class="uk-margin">
              <label for="intending_state" class="uk-form-label">
                {{ __('State') }}
              </label>
              <div class="uk-form-control">
                <select onchange="update_lga_list(this)" class="uk-select" id="intending_state" name="intending_state">
                  <option value="">Select State</option>
                  @foreach ($states as $state)
                  <option value="{{$state->id}}">{{$state->name}}</option>
                  @endforeach
                </select @error('intending_state') <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin">
              <label for="intending_lga" class="uk-form-label">
                {{ __('Lga') }}
              </label>
              <div class="uk-form-control">
                <select class="uk-select" id="intending_lga" name="intending_lga">
                  <option value="">Select Lga</option>
                </select @error('intending_lga') <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin">
              <label for="address" class="uk-form-label">
                {{ __('Physical address') }}
              </label>
              <div class="uk-form-control">
                <textarea id="address" class="uk-textarea @error('address') uk-form-danger @enderror" name="address"
                  required>{{ old('address') }}</textarea>
                @error('address')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin">
              <label for="government_issued_id" class="uk-form-label">
                {{ __('Government Issued Id Card') }}
              </label>
              <div class="uk-form-control">
                <input name="government_issued_id" placeholder="Select Images"
                  class="uk-input @error('image')  uk-form-danger @enderror" id="government_issued_id"
                  accept=".jpg, .png, .jpeg" type="file" />
                @error('government_issued_id')
                <span class="uk-text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="uk-margin">
              <div class="uk-form-control">
                <button type="submit"
                  class="uk-button uk-border-rounded green accent-2 white-text uk-text-bolder uk-width-1-1">
                  <span class="uk-text-large">P</span>ay Application Fee
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

@if(in_array($role,$roles))
<div style="padding:3px;">
  <div class="uk-border-rounded uk-card uk-background-primary uk-light uk-padding-remove">
    <div class="uk-card-body uk-padding-remove">
      <h5 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate uk-text-bolder">
        {{$title}}</h5>
      <table class="uk-table uk-table-small uk-table-divider uk-margin-remove-top uk-margin-bottom">
        <tbody style="font-size:0.8em">
          @foreach ($stat_data as $row)
          <tr>
            <td class="uk-text-bold">{{$row['text']}}:
              <span class="uk-text-right uk-text-bold white-text">{{$row['value']}}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="black uk-padding-remove uk-position-bottom">
      <div class="uk-width-1-1 uk-child-width-1-1 uk-child-width-1-2@s uk-flex uk-flex-bottom">
        @foreach ($stat_link as $link)
        <a href="{{$link['route']}}" class="uk-button uk-button-link uk-text-bold  white-text"
          style="font-size:0.8em"><span class=" uk-visible@m"
            uk-icon="icon:{{$link['icon']}};ratio:0.8"></span>{{$link['name']}}
        </a>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

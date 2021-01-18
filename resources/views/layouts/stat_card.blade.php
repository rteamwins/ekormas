<div style="padding:3px;">
  <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
    <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">{{$title}}</h4>
    <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
      <tbody class="uk-text-small">
        @foreach ($stat_data as $row)
        <tr>
          <td class="uk-text-bold uk-text-truncate uk-width-1-3">{{$row['text']}}:</td>
          <td class="uk-text-right uk-text-bold white-text">{{$row['value']}}</td>
        </tr>
        @endforeach
        <tr class="black">
          <td class="uk-padding-remove" colspan="2">
            <div class="uk-width-1-1 uk-flex uk-flex-around">
              @foreach ($stat_link as $link)
              <a href="{{$link['route']}}" class="uk-button uk-button-link uk-text-bold  white-text"><span
                  uk-icon="{{$link['icon']}}"></span> <span class="uk-visible@m">{{$link['name']}}</span> </a>
              @endforeach
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

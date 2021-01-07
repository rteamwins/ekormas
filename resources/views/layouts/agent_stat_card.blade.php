<div class="uk-grid-small" uk-grid>
  <div class="uk-width-1-1">
    <div class="uk-card uk-card-default uk-card-body uk-border-rounded" style="padding:0;">
      <div
        class="uk-grid-collapse uk-grid-match uk-child-width-1-6@xl uk-child-width-1-5@xl uk-child-width-1-4@m uk-child-width-1-3@s uk-child-width-1-2"
        uk-grid>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">MEMBERSHIP
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3"><img class="uk-preserve-width" width="20"
                      height="20"
                      src="{{asset(sprintf("images/misc/%s.svg",strtolower(Auth()->user()->membership_plan->name)))}}"
                      alt="{{Auth()->user()->membership_plan->name . "Badge"}}"></td>
                  <td class="uk-text-right uk-text-bold white-text">
                    {{ucfirst(Auth()->user()->membership_plan->name)}}</td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TYPE</td>
                  <td class="uk-text-right uk-text-bold white-text">{{Auth()->user()->role=='user'?'Investor':'Agent'}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_create_withdraw_fund')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="upload"></span> <span
                          class="uk-visible@m">Upgrade</span> </a>
                      <a href="{{route('user_fund_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="folder"></span> <span
                          class="uk-visible@m">Profile</span> </a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">WALLET</h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TOTAL:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->wallet?:0,2)}}</td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">AVAIL:</td>
                  <td class="uk-text-right uk-text-bold white-text">
                    ${{number_format(Auth()->user()->available_wallet?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_fund_wallet')}}"
                        class="uk-button uk-button-link uk-text-bold  white-text"><span uk-icon="download"></span> <span
                          class="uk-visible@m">Fund</span> </a>
                      <a href="{{route('user_create_withdraw_fund')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="upload"></span> <span
                          class="uk-visible@m">Withdraw</span> </a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">TRADE
              CAPITAL
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TOTAL:</td>
                  <td class="uk-text-right uk-text-bold white-text">
                    ${{number_format(Auth()->user()->trading_capital?:0,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">ROI:</td>
                  <td class="uk-text-right uk-text-bold white-text">
                    ${{number_format(Auth()->user()->trading_capital?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_create_trade')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="cart"></span> <span
                          class="uk-visible@m">Start</span> </a>
                      <a href="{{route('user_trade_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span> <span
                          class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">BONUS
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TOTAL:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->bonus?:0,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">POINTS:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{number_format(Auth()->user()->points?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('create_bonus_to_wallet_funds')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="cart"></span> <span
                          class="uk-visible@m">Convert</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">GLM
              TOKEN
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">COUNT:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{auth()->user()->avail_created_kycs()->count()}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">TOTAL:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->avail_created_kycs_sum())}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_list_kyc')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span> <span
                          class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">
              REGISTRATION
              CREDIT
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">AVAIL:</td>
                  <td class="uk-text-right uk-text-bold white-text">
                    {{number_format(Auth()->user()->registration_credits_count)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">REFERALS:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{number_format(Auth()->user()->referals_count)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_purchase_registration_credits')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="cart"></span>
                        <span class="uk-visible@m">Purchase</span> </a>
                      <a href="{{route('user_list_purchase_registration_credits')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span>
                        <span class="uk-visible@m">History</span> </a>
                      <a href="{{route('user_referal_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="users"></span> <span
                          class="uk-visible@m">Referals</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">BONUS
              EARNING
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TODAY:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->bonus?:0,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">WEEK:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{number_format(Auth()->user()->points?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_bonus_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span> <span
                          class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">WITHDRAWS
              MADE
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TODAY:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->bonus?:0,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">WEEK:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{number_format(Auth()->user()->points?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_withdraw_fund_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span> <span
                          class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">FUNDINGS
              MADE
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">TODAY:</td>
                  <td class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->bonus?:0,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">WEEK:</td>
                  <td class="uk-text-right uk-text-bold white-text">{{number_format(Auth()->user()->points?:0,2)}}
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_fund_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span> <span
                          class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="padding:3px;">
          <div class="uk-border-rounded uk-card green accent-2 uk-light uk-padding-remove">
            <h4 class="uk-margin-remove-bottom uk-padding-small uk-padding-remove-vertical uk-text-truncate">LOCAL
              PAYOUT
            </h4>
            <table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
              <tbody class="uk-text-small">
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-1-3">PENDING:</td>
                  <td class="uk-text-right uk-text-bold white-text">12
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold uk-text-truncate uk-width-2-3">COMPLETED:</td>
                  <td class="uk-text-right uk-text-bold white-text">1231
                  </td>
                </tr>
                <tr class="black">
                  <td class="uk-padding-remove" colspan="2">
                    <div class="uk-width-1-1 uk-flex uk-flex-around">
                      <a href="{{route('user_withdraw_local_history')}}"
                        class="uk-button uk-button-link uk-text-bold white-text"><span uk-icon="list"></span>
                        <span class="uk-visible@m">History</span> </a>
                    </div>
                  </td>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="uk-grid-small" uk-grid>
  <div class="uk-width-1-1">
    <div
      class="uk-grid-collapse uk-grid-match uk-flex uk-flex-center uk-child-width-1-6@xl uk-child-width-1-5@xl uk-child-width-1-4@m uk-child-width-1-3@s uk-child-width-1-2"
      uk-grid>
      @if(in_array(auth()->user()->role,['user','agent','admin']))
      <div style="padding:3px;">
        <div class="uk-border-rounded uk-card uk-background-primary uk-light uk-padding-remove">
          <h5 class="uk-margin-remove-bottom uk-text-bold uk-padding-small uk-padding-remove-vertical uk-text-truncate">
            MEMBERSHIP
          </h5>
          <table class="uk-table uk-table-small uk-table-divider uk-margin-remove-top uk-margin-bottom">
            <tbody style="font-size:0.8em">
              <tr>
                <td class="uk-text-bold uk-text-truncate uk-width-1-3"><img class="uk-preserve-width" width="20"
                    height="20"
                    src="{{asset(sprintf("images/misc/%s.svg",strtolower(Auth()->user()->membership_plan->name)))}}"
                    alt="{{Auth()->user()->membership_plan->name . "Badge"}}">
                  <span class="uk-text-right uk-text-bold white-text">
                    {{ucfirst(Auth()->user()->membership_plan->name)}}</span>
                    <span class="uk-text-right uk-text-bold white-text">
                      ${{number_format(Auth()->user()->membership_plan->fee,0)}}</span></td>
              </tr>
              <tr>
                <td class="uk-text-bold uk-text-truncate uk-width-1-3">WALLET
                  <span class="uk-text-right uk-text-bold white-text">${{number_format(Auth()->user()->wallet?:0,2)}}
                  </span> </td>
              </tr>
            </tbody>
          </table>
          <div class="uk-position-bottom black">
            <div class="uk-width-1-1 uk-flex uk-flex-around">
              <a href="{{route('update_reg_plan')}}" class="uk-button uk-button-link uk-text-bold white-text"
                style="font-size:0.8em"><span class="uk-visible@m" uk-icon="icon:upload;ratio:0.8"></span> Upgrade</a>
              <a href="{{route('user_profile')}}" class="uk-button uk-button-link uk-text-bold white-text"
                style="font-size:0.8em"><span class="uk-visible@m" uk-icon="icon:folder;ratio:0.8"></span> Profile</a>
            </div>
          </div>
        </div>
      </div>
      @else
      <div style="padding:3px;">
        <div class="uk-border-rounded uk-card uk-background-primary uk-light uk-padding-remove">
          <h5 class="uk-margin-remove-bottom uk-text-bold uk-padding-small uk-padding-remove-vertical uk-text-truncate">
            PROFILE
          </h5>
          <table class="uk-table uk-table-small uk-table-divider uk-margin-remove-top uk-margin-bottom">
            <tbody style="font-size:0.8em">
              <tr>
                <td class="uk-text-bold uk-text-truncate">USERNAME:
                  <span class="uk-text-right uk-text-bold white-text">{{Auth()->user()->username}}</span></td>
              </tr>
              <tr>
                <td class="uk-text-bold uk-text-truncate uk-width-1-3">JOINED:
                  <span
                    class="uk-text-right uk-text-bold white-text">{{Auth()->user()->created_at->diffForHumans()}}</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="uk-position-bottom black">
            <div class="uk-width-1-1 uk-flex uk-flex-around">
              <a href="{{route('update_reg_plan')}}" class="uk-button uk-button-link uk-text-bold white-text"
                style="font-size:0.8em"><span class="uk-visible@m" uk-icon="icon:upload;ratio:0.8"></span> Upgrade</a>
              <a href="{{route('user_profile')}}" class="uk-button uk-button-link uk-text-bold white-text"
                style="font-size:0.8em"><span class="uk-visible@m" uk-icon="icon:folder;ratio:0.8"></span> Profile</a>
            </div>
          </div>
        </div>
      </div>
      @endif

      @include('layouts.stat_card',[
      'roles' => ['admin','user','agent'],
      'title'=>'WALLET WITHDRAW',
      'stat_data'=>[
      ['text'=>"TODAY",'value'=>"$".number_format(Auth()->user()->bonus?:0,2)],
      ['text'=>"WEEK",'value'=>number_format(Auth()->user()->points?:0,2)]
      ],
      'stat_link'=>[
      ['route'=>route('user_create_withdraw_fund'),'name'=>'Withdraw','icon'=>'upload'],
      ['route'=>route('user_withdraw_fund_history'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','user','agent'],
      'title'=>'WALLET FUNDING',
      'stat_data'=>[
      ['text'=>"TODAY",'value'=>"$".number_format(Auth()->user()->bonus?:0,2)],
      ['text'=>"WEEK",'value'=>number_format(Auth()->user()->points?:0,2)]
      ],
      'stat_link'=>[
      ['route'=>route('user_fund_wallet'),'name'=>'Fund','icon'=>'download'],
      ['route'=>route('user_fund_history'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','user','agent',],
      'title'=>'TRADE CAPITAL',
      'stat_data'=>[
      ['text'=>"TOTAL",'value'=>"$".number_format(Auth()->user()->trading_capital?:0,2)],
      ['text'=>"ROI",'value'=>"$".number_format(Auth()->user()->trading_capital?:0,2)]
      ],
      'stat_link'=>[
      ['route'=>route('user_create_trade'),'name'=>'Start','icon'=>'cart'],
      ['route'=>route('user_trade_history'),'name'=>'History','icon'=>'list']
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','user','agent',],
      'title'=>'BONUS',
      'stat_data'=>[
      ['text'=>"TOTAL",'value'=>"$".number_format(Auth()->user()->bonus?:0,2)],
      ],
      'stat_link'=>[
      ['route'=>route('create_bonus_to_wallet_funds'),'name'=>'Convert','icon'=>'cart'],
      ['route'=>route('user_bonus_history'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'POINTS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>number_format(Auth()->user()->active_points?:0,2)],
      ['text'=>"DORMANT",'value'=>number_format(Auth()->user()->dormant_points?:0,2)]
      ],
      'stat_link'=>[
      ['route'=>route('create_point_to_wallet_funds'),'name'=>'Claim','icon'=>'cart'],
      ['route'=>route('user_point_history'),'name'=>'History','icon'=>'list'],
      ['route'=>route('admin_list_point_nominees'),'name'=>'Nominees','icon'=>'users']
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['user','agent',],
      'title'=>'POINTS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>number_format(Auth()->user()->active_points?:0,2)],
      ['text'=>"DORMANT",'value'=>number_format(Auth()->user()->dormant_points?:0,2)]
      ],
      'stat_link'=>[
      ['route'=>route('create_point_to_wallet_funds'),'name'=>'Claim','icon'=>'cart'],
      ['route'=>route('user_point_history'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','user','agent',],
      'title'=>'GLM TOKEN',
      'stat_data'=>[
      ['text'=>"COUNT",'value'=>auth()->user()->avail_created_kycs()->count()],
      ['text'=>"TOTAL",'value'=>"$".number_format(Auth()->user()->avail_created_kycs_sum())]
      ],
      'stat_link'=>[
      ['route'=>route('user_list_kyc'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','agent',],
      'title'=>'REGISTRATION CREDIT',
      'stat_data'=>[
      ['text'=>"AVAIL",'value'=>$avail_reg_credit?:0],
      ['text'=>"REFERALS",'value'=>$downlines_count?:0]
      ],
      'stat_link'=>[
      ['route'=>route('user_list_registration_credits'),'name'=>'History','icon'=>'list'],
      ['route'=>route('user_referal_history'),'name'=>'Referals','icon'=>'users'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['user',],
      'title'=>'REFERALS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>$downlines_count?:0]
      ],
      'stat_link'=>[
      ['route'=>route('user_referal_history'),'name'=>'Referals','icon'=>'users'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin','agent',],
      'title'=>'LOCAL PAYOUT',
      'stat_data'=>[
      ['text'=>"PENDING",'value'=>$admin_local_pay_pending?:0],
      ['text'=>"COMPLETED",'value'=>$local_pay_completed?:0]
      ],
      'stat_link'=>[
      ['route'=>route('user_withdraw_local_history'),'name'=>'History','icon'=>'list'],
      ['route'=>route('local_pay_requests'),'name'=>'Pending','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['user'],
      'title'=>'LOCAL PAYOUT',
      'stat_data'=>[
      ['text'=>"PENDING",'value'=>$local_pay_pending?:0],
      ['text'=>"COMPLETED",'value'=>$local_pay_completed?:0]
      ],
      'stat_link'=>[
      ['route'=>route('user_withdraw_local_history'),'name'=>'History','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'AGENTS',
      'stat_data'=>[
      ['text'=>"AVAIL",'value'=>$avail_agent_count??0],
      ['text'=>"POTENTIAL",'value'=>$potential_agent_count??0]
      ],
      'stat_link'=>[
      ['route'=>route('admin_list_avail_agents'),'name'=>'Avail','icon'=>'git-branch'],
      // ['route'=>route('admin_list_potential_agents'),'name'=>'Potential','icon'=>'plus-circle'],
      ['route'=>route('agent_application_list'),'name'=>'Request','icon'=>'users'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'INVESTORS',
      'stat_data'=>[
      ['text'=>"AVAIL",'value'=>$active_user_count??0],
      ['text'=>"POTENTIAL",'value'=>$non_active_user_count??0]
      ],
      'stat_link'=>[
      ['route'=>route('admin_list_active_users'),'name'=>'Active','icon'=>'users'],
      ['route'=>route('admin_list_non_active_users'),'name'=>'Pending','icon'=>'plus-circle'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'POSTS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>$active_post??0],
      ['text'=>"DELETED",'value'=>$deleted_post??0]
      ],
      'stat_link'=>[
      ['route'=>route('create_post'),'name'=>'Create','icon'=>'file-edit'],
      ['route'=>route('list_post'),'name'=>'List','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'ALERTS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>$active_alert??0],
      ['text'=>"DISABLED",'value'=>$disabled_alert??0]
      ],
      'stat_link'=>[
      ['route'=>route('admin_list_alert'),'name'=>'Manage','icon'=>'file-edit'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'PRODUCTS',
      'stat_data'=>[
      ['text'=>"ACTIVE",'value'=>$active_product??0],
      ['text'=>"DISABLED",'value'=>$disabled_product??0]
      ],
      'stat_link'=>[
      ['route'=>route('create_product'),'name'=>'Create','icon'=>'file-edit'],
      ['route'=>route('list_product'),'name'=>'List','icon'=>'list'],
      ['route'=>route('admin_list_category'),'name'=>'Category','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['admin',],
      'title'=>'ORDERS',
      'stat_data'=>[
      ['text'=>"OPEN",'value'=>$open_order??0],
      ['text'=>"CLOSED",'value'=>$closed_order??0]
      ],
      'stat_link'=>[
      ['route'=>route('order_list'),'name'=>'List','icon'=>'list'],
      ['route'=>route('admin_order_request_list'),'name'=>'Request','icon'=>'list'],
      ]
      ])

      @include('layouts.stat_card',[
      'roles' => ['user','agent','buyer'],
      'title'=>'ORDERS',
      'stat_data'=>[
      ['text'=>"OPEN",'value'=>$open_order??0],
      ['text'=>"CLOSED",'value'=>$closed_order??0]
      ],
      'stat_link'=>[
      ['route'=>route('order_list'),'name'=>'List','icon'=>'list'],
      ]
      ])

    </div>
  </div>
</div>

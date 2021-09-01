                @php $sub_menu_class = 'm-menu__item--open m-menu__item--expanded'; @endphp
                <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
                <div id="m_aside_left" class="m-grid__item  m-aside-left  m-aside-left--skin-dark ">

                    <!-- BEGIN: Aside Menu -->
                    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

                            <li class="m-menu__item  {{ Route::currentRouteName() == 'admin.dashboard' ? $sub_menu_class : '' }}" aria-haspopup="true"><a href="{{route('admin.dashboard')}}" class="m-menu__link "><img class="m-menu__link-icon" src="{{asset('public/assets/icons/dashboard.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Dashboard</span></span></span></a></li>
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'staff-members', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/staff-members*') ? $sub_menu_class : ''}}" aria-haspopup="true">
                                <a href="{{route('admin.staffmembers.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/staff-members.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Staff Members</span> </span></span></a>
                            </li>
                            @endif
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-members', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/users*') ? $sub_menu_class : ''}}" aria-haspopup="true">
                                <a href="{{route('admin.users.index')}}" class="m-menu__link "><img class="m-menu__link-icon" src="{{asset('public/assets/icons/manage-member.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Members</span> </span></span></a>
                            </li>
                            @endif
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/plans*') ? $sub_menu_class : ''}}" aria-haspopup="true">
                                <a href="{{route('admin.plans.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/plan.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Plans</span> </span></span></a>
                            </li>
                            @endif
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/tips*') ? $sub_menu_class : ''}}" aria-haspopup="true">
                                <a href="{{route('admin.tips.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/manage-tips.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Tips</span> </span></span></a>
                            </li>
                            @endif

                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/roles*') ? $sub_menu_class : '' }} {{ request()->is('admin/sitepermissions*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.roles.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/manage-roles.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Roles</span> </span></span></a>
                            </li>
                            @endif

                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-source', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/sources*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.sources.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/cms-system.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Source</span> </span></span></a>
                            </li>
                            @endif

                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/pages*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.pages.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/pages.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Pages</span> </span></span></a>
                            </li>
                            @endif

                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'settings', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/settings*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.settings')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/settings.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Settings</span> </span></span></a>
                            </li>
                            @endif

                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_read'))
                            <li class="m-menu__item {{ request()->is('admin/faqs*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.faqs.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/faq.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Manage Faq</span> </span></span></a>
                            </li>
                            @endif
							
							@if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_read'))
							<li class="m-menu__item {{ request()->is('admin/stockedgevideos*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.stockedgevideos.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/learn-videos.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Videos</span> </span></span></a>
                            </li>
							@endif
							
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_read'))
                            {{--
                            <li class="m-menu__item {{ request()->is('admin/videos*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.videos.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/learn-videos.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Learn Videos</span> </span></span></a>
                            </li>
                            --}}

                            <li class="m-menu__item  m-menu__item--submenu {{ request()->is('admin/videos*') ? 'm-menu__item--open m-menu__item--expanded' : '' }}" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/learn-videos.png')}}"><span class="m-menu__link-text" style="padding-left: 57px;">Learn Videos</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Learn Videos</span></span></li>
                                        <li class="m-menu__item" aria-haspopup="true"><a href="{{route('admin.videos.categories.index')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Category</span></a></li>

                                        <li class="m-menu__item" aria-haspopup="true" m-menu-link-redirect="1"><a href="{{route('admin.videos.index')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Videos</span></a></li>

                                    </ul>
                                </div>
                            </li>
                            @endif

                            {{--
                            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'roles', 'is_read'))
                            <li class="m-menu__item  m-menu__item--submenu {{ request()->is('admin/roles*') ? 'm-menu__item--open m-menu__item--expanded' : '' || request()->is('admin/sitepermissions*') ? 'm-menu__item--open m-menu__item--expanded' : '' }}" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><img src="{{asset('public/assets/icons/member.png')}}"><span class="m-menu__link-text">Settings</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">

                                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Settings</span></span></li>

                                        <li class="m-menu__item" aria-haspopup="true">
                                            <a href="{{route('admin.roles.index')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Roles</span></a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                            @endif
                            --}}

                            <li class="m-menu__item {{ request()->is('admin/support-inquiry*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.support-inquiry.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/technical-support.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Support Enquiry</span> </span></span></a>
                            </li>
							
							<li class="m-menu__item {{ request()->is('admin/partner-with-us*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.partner-with-us.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/alliance.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Partner With Us</span> </span></span></a>
                            </li>
							
							
								<li class="m-menu__item {{ request()->is('admin/referral-userlist*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.referral-userlist')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/transfer.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Referral Users</span> </span></span></a>
                            </li>
							
							
							
							<li class="m-menu__item {{ request()->is('admin/notifications*') ? $sub_menu_class : '' }}" aria-haspopup="true">
                                <a href="{{route('admin.notifications.index')}}" class="m-menu__link "><img class="m-menu__link-icon"  src="{{asset('public/assets/icons/bell.png')}}"><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Send Notification</span> </span></span></a>
                            </li>
							
                        </ul>
                    </div>

                    <!-- END: Aside Menu -->
                </div>

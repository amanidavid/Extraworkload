<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
      
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                   @role('Staff')
                    <li>
                        <a title="Landing Page" href="{{route('form.show')}}" aria-expanded="false"><span class="educate-icon educate-event icon-wrap sub-icon-mg" aria-hidden="true"></span> <span class="mini-click-non">Attandence Form</span></a>
                    </li>
                    <li>
                        <a title="Generate Report" href="{{route('form.shows')}}" aria-expanded="false"><span class=""></span> <span class="mini-click-non">Claim Form</span></a>
                    </li>
                @endrole
                </ul>
            </nav>
        </div>
    </nav>
</div>
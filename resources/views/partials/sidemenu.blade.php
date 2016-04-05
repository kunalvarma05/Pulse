<nav class="sidemenu" id="sidemenu">
    <a href="{{ url('/') }}" class="sidemenu-logo">
        <img src="{{ asset('images/logo-white.png') }}" alt="pulse logo">
    </a>

    <ul class="nav sidemenu-user-accounts">
        <li>
            <a class="sidemenu-user-account" data-toggle-tooltip="tooltip" title="Kunal's OneDrive" href="#" style="animation-delay: .5s;">
                <img src="https://randomuser.me/api/portraits/med/men/14.jpg">
            </a>
        </li>
        <li>
            <a class="sidemenu-user-account" data-toggle-tooltip="tooltip" title="Kunal's Drive" href="#" style="animation-delay: .6s;">
                <img src="https://randomuser.me/api/portraits/med/men/19.jpg">
            </a>
        </li>
        <li>
            <a class="sidemenu-user-account active" data-toggle-tooltip="tooltip" title="Kunal's Dropbox" href="#" style="animation-delay: .7s;">
                <img src="https://randomuser.me/api/portraits/med/men/9.jpg">
            </a>
        </li>
        <li>
            <a class="sidemenu-user-account" data-toggle-tooltip="tooltip" title="John's Dropbox" href="#" style="animation-delay: .8s;">
                <img src="https://randomuser.me/api/portraits/med/men/18.jpg">
            </a>
        </li>
        <li>
            <a class="sidemenu-add-button" data-toggle-tooltip="tooltip" title="Connect New Account" href="#" data-toggle="modal" data-target="#connect-account-modal">
                <span class="fa fa-plus"></span>
            </a>
        </li>
    </ul>
</nav>
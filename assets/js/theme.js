document.addEventListener('DOMContentLoaded', function () {
    // --- Search Block Styling ---
    const searchForms = document.querySelectorAll('.wp-block-search');
    searchForms.forEach(form => {
        const wrapper = form.querySelector('.wp-block-search__inside-wrapper');
        if (wrapper) {
            wrapper.classList.add('input-group');
            const input = wrapper.querySelector('.wp-block-search__input');
            const button = wrapper.querySelector('.wp-block-search__button');
            if (input) input.classList.add('form-control');
            if (button) button.classList.add('btn', 'btn-primary');
        }
    });

    // --- Navigation Block Styling ---
    const navs = document.querySelectorAll('.navbar-nav');
    navs.forEach(nav => {
        // Enforce 1-level depth: remove sub-sub-menus
        const subSubMenus = nav.querySelectorAll('.wp-block-navigation__submenu-container .wp-block-navigation__submenu-container');
        subSubMenus.forEach(s => s.remove());

        // Target top-level menu items
        const menuItems = nav.querySelectorAll('.wp-block-navigation-item.has-child');
        menuItems.forEach(item => {
            item.classList.add('dropdown');

            const link = item.querySelector('.wp-block-navigation-item__content');
            if (link) {
                link.classList.add('dropdown-toggle');
                link.setAttribute('data-bs-toggle', 'dropdown');
                link.setAttribute('aria-expanded', 'false');
                link.setAttribute('role', 'button');
            }

            // Remove WordPress default submenu buttons (the extra carets)
            const wpButtons = item.querySelectorAll('.wp-block-navigation-item__submenu-icon, .wp-block-navigation-submenu__toggle');
            wpButtons.forEach(btn => btn.remove());

            const subMenu = item.querySelector('.wp-block-navigation__submenu-container');
            if (subMenu) {
                subMenu.classList.add('dropdown-menu');
                // Ensure dropdown items have the correct class
                const subLinks = subMenu.querySelectorAll('.wp-block-navigation-item__content');
                subLinks.forEach(sl => sl.classList.add('dropdown-item'));
            }
        });

        // Ensure non-dropdown links have nav-link class
        const allLinks = nav.querySelectorAll('.wp-block-navigation-item__content:not(.dropdown-item)');
        allLinks.forEach(link => link.classList.add('nav-link'));
    });
});

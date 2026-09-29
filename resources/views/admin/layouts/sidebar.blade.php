<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/inventryx_logo.png') }}" alt="" height="36">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/inventryx_logo.png') }}" alt="" height="48">
                    </span>
                </a>
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/inventryx_logo.png') }}" alt="" height="36">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/inventryx_logo.png') }}" alt="" height="48">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                {{-- Dashboard : visible par tous --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                {{-- ═══════════════════════════════════════════════ --}}
                {{-- SECTION AGENT + ADMIN (activités quotidiennes) --}}
                {{-- ═══════════════════════════════════════════════ --}}
                @hasanyrole('admin|agent')
                <li class="menu-title">Ventes & Clients</li>

                {{-- Marques --}}
                <li>
                    <a href="#Brand" data-bs-toggle="collapse">
                        <i data-feather="tag"></i>
                        <span> Marques </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Brand">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.brand') }}" class="tp-link">Toutes les marques</a></li>
                            <li><a href="{{ route('add.brand') }}" class="tp-link">Ajouter une marque</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Clients --}}
                <li>
                    <a href="#Customer" data-bs-toggle="collapse">
                        <i data-feather="user"></i>
                        <span> Clients </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Customer">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.customer') }}" class="tp-link">Tous les clients</a></li>
                            <li><a href="{{ route('add.customer') }}" class="tp-link">Ajouter un client</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Produits / Catégories --}}
                <li>
                    <a href="#Product" data-bs-toggle="collapse">
                        <i data-feather="box"></i>
                        <span> Produits </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Product">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.category') }}" class="tp-link">Toutes les catégories</a></li>
                            <li><a href="{{ route('all.product') }}" class="tp-link">Tous les produits</a></li>
                            <li><a href="{{ route('add.product') }}" class="tp-link">Ajouter un produit</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Ventes --}}
                <li>
                    <a href="#Sale" data-bs-toggle="collapse">
                        <i data-feather="dollar-sign"></i>
                        <span> Ventes </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Sale">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.sale') }}" class="tp-link">Toutes les ventes</a></li>
                            <li><a href="{{ route('add.sale') }}" class="tp-link">Nouvelle vente</a></li>
                            <li><a href="{{ route('all.sale.return') }}" class="tp-link">Retours de vente</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Créances --}}
                <li>
                    <a href="#Due" data-bs-toggle="collapse">
                        <i data-feather="credit-card"></i>
                        <span> Créances </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Due">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('due.sale') }}" class="tp-link">Créances ventes</a></li>
                            <li><a href="{{ route('due.sale.return') }}" class="tp-link">Créances retours</a></li>
                        </ul>
                    </div>
                </li>
                @endhasanyrole

                {{-- ═══════════════════════════════════════════════ --}}
                {{-- SECTION ADMIN UNIQUEMENT --}}
                {{-- ═══════════════════════════════════════════════ --}}
                @role('admin')
                <li class="menu-title">Administration</li>

                {{-- Entrepôts --}}
                <li>
                    <a href="#WareHouse" data-bs-toggle="collapse">
                        <i data-feather="archive"></i>
                        <span> Entrepôts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="WareHouse">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.warehouse') }}" class="tp-link">Tous les entrepôts</a></li>
                            <li><a href="{{ route('add.warehouse') }}" class="tp-link">Ajouter un entrepôt</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Fournisseurs --}}
                <li>
                    <a href="#Supplier" data-bs-toggle="collapse">
                        <i data-feather="truck"></i>
                        <span> Fournisseurs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Supplier">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.supplier') }}" class="tp-link">Tous les fournisseurs</a></li>
                            <li><a href="{{ route('add.supplier') }}" class="tp-link">Ajouter un fournisseur</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Achats --}}
                <li>
                    <a href="#Purchase" data-bs-toggle="collapse">
                        <i data-feather="shopping-cart"></i>
                        <span> Achats </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Purchase">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.purchase') }}" class="tp-link">Tous les achats</a></li>
                            <li><a href="{{ route('add.purchase') }}" class="tp-link">Nouvel achat</a></li>
                            <li><a href="{{ route('all.return.purchase') }}" class="tp-link">Retours d'achat</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Transferts --}}
                <li>
                    <a href="#Transfers" data-bs-toggle="collapse">
                        <i data-feather="repeat"></i>
                        <span> Transferts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Transfers">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.transfer') }}" class="tp-link">Tous les transferts</a></li>
                            <li><a href="{{ route('add.transfer') }}" class="tp-link">Nouveau transfert</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Utilisateurs --}}
                <li>
                    <a href="#Users" data-bs-toggle="collapse">
                        <i data-feather="users"></i>
                        <span> Utilisateurs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Users">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('users.index') }}" class="tp-link">Tous les utilisateurs</a></li>
                            <li><a href="{{ route('users.create') }}" class="tp-link">Ajouter un utilisateur</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Rôles & Permissions --}}
                <li>
                    <a href="#RolePermission" data-bs-toggle="collapse">
                        <i data-feather="shield"></i>
                        <span> Rôles & Permissions </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="RolePermission">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('all.permission') }}" class="tp-link">Toutes les permissions</a></li>
                            <li><a href="{{ route('all.roles') }}" class="tp-link">Tous les rôles</a></li>
                            <li><a href="{{ route('all.roles.permission') }}" class="tp-link">Attribution rôles/permissions</a></li>
                        </ul>
                    </div>
                </li>
                @endrole

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>

<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="active treeview">
          <a href="">
            <i class="fa fa-dashboard"></i> <span>Data Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('petugas') }}"><i class="fa fa-circle-o"></i> Petugas</a></li>
            <li><a href="{{ url('anggota') }}"><i class="fa fa-circle-o"></i> Anggota</a></li>
            <li><a href="{{ url('buku') }}"><i class="fa fa-circle-o"></i> Buku</a></li>
            <li><a href="{{ url('rak') }}"><i class="fa fa-circle-o"></i> Rak</a></li>
          </ul>
        </li>
        <li class="active treeview">
          <a href="">
            <i class="fa fa-dashboard"></i> <span>Transaksi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('peminjaman') }}"><i class="fa fa-circle-o"></i> Peminjaman</a></li>
            <li><a href="{{ url('pengembalian') }}"><i class="fa fa-circle-o"></i> Pengembalian</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

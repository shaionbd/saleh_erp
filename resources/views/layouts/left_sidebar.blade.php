<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">

  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ URL::asset('public/images/default_user.png') }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>{{ Auth::user()->name }}</p>
      <!-- Status -->
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <!-- search form (Optional) -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
      </span>
    </div>
  </form>
  <!-- /.search form -->

  <!-- Sidebar Menu -->
  <ul class="sidebar-menu">
    <li class="header">Dashboard</li>

    @if(Auth::user()->role == 3)
      <!-- user sidebar -->
      <li @if($active == 'tasks') class="active" @endif ><a href="{{ route('user.tasks') }}"><i class="fa fa-tasks"></i> <span>Tasks</span></a></li>
      <li @if($active == 'archives') class="active" @endif ><a href="{{ route('user.archive', 'current') }}"><i class="fa fa-archive"></i> <span>Archive</span></a></li>
      <li @if($active == 'payments') class="active" @endif><a href="{{ route('user.payment') }}"><i class="fa fa-money"></i> <span>Payment</span></a></li>
      <li @if($active == 'profile') class="active" @endif><a href="{{ route('user.profile') }}"><i class="fa fa-user-o"></i> <span>Profile</span></a></li>
    @elseif(Auth::user()->role == 2)
      <!-- manager sidebar -->
      <li @if($active == 'tasks') class="active" @endif ><a href="{{ route('manager.tasks') }}"><i class="fa fa-tasks"></i> <span>Tasks</span></a></li>
      <li @if($active == 'archives') class="active" @endif ><a href="{{ route('user.archive', 'current') }}"><i class="fa fa-archive"></i> <span>Archive</span></a></li>
      <li @if($active == 'payments') class="active" @endif><a href="{{ route('user.payment') }}"><i class="fa fa-money"></i> <span>Payment</span></a></li>
      <li @if($active == 'profile') class="active treeview" @endif>
        <a href="#"><i class="fa fa-user-o"></i> <span>Profile</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li><a href="{{ route('user.profile') }}"><i class="fa fa-angle-right"></i> My Biography</a></li>
          <li><a href="{{ route('user.managerTeam') }}"><i class="fa fa-angle-right"></i> My Team</a></li>
        </ul>
      </li>
    <!-- /manager sidebar -->
    @elseif(Auth::user()->role == 1)
    <!-- admin sidebar -->
    <li @if($active == 'tasks') class="active" @endif ><a href="{{ route('admin.tasks') }}"><i class="fa fa-tasks"></i> <span>Tasks</span></a></li>

    @elseif(Auth::user()->role == 4)
      <!-- redirect to client home page -->
    @endif

    <!-- <li class="treeview">
      <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="#">Link in level 2</a></li>
        <li><a href="#">Link in level 2</a></li>
      </ul>
    </li> -->
  </ul><!-- /.sidebar-menu -->

</section>

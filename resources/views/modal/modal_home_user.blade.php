<!-- Modal -->
<div class="modal fade" id="modal_home_user" tabindex="-1" role="dialog"
aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content modal-color">
    <!-- Modal Header -->
    <div class="modal-header">
      <button type="button" class="close"
      data-dismiss="modal">
      <span aria-hidden="true" class="c-white">&times;</span>
      <span class="sr-only">Close</span>
      </button>
      <h4 class="modal-title c-white" id="myModalLabel">
        Menu User Management
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(132, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('user_management/user')}}">User<span class="fa"></span></a>
        </li>
        <li @php if(!in_array(133, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('user_management/group')}}">Role<span class="fa"></span></a>
        </li>
        <li @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('user_management/loginlog')}}">Login Log<span class="fa"></span></a>
        </li>
        <li @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('user_management/userlog')}}">Activity Log<span class="fa"></span></a>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
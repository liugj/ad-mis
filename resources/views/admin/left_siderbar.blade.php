<?php $menu    =[2=>'/admin/ideas', 1=>'/admin/users', 3=>'/admin/administrators', 4=>'/admin/medias']; 
      $role   = Auth :: admin()->get()->role; 
      $grants =  \App\Role :: $roles_grants[$role];
?>
<div class="admin-left" id="navMain">
                <ul>
                @foreach($menu as $index => $link)
                     @if (isset($grants[$link]))
                      <li data-index="{{$index}}"><a href="{{$link}}">{{$grants[$link]}}</a></li>
                     @endif
                 @endforeach
                @if(isset($appends))
                   @foreach($appends as $index=>$name)
                         <li data-index="{{$index}}"><a href="">{{$name}}</a></li>
                   @endforeach
                @endif
                </ul>
 </div>


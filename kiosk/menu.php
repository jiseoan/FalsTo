<style type="text/css">
.menu-item {
  width: 135px;
  height: 48px;
  font-size: 14px;
  font-weight:bold;
  cursor:pointer;
  background-image: url(./image/menuitem.png);
  background-repeat: no-repeat;
}
.menu-item-sel {
  width: 135px;
  height: 48px;
  font-size: 14px;
  font-weight:bold;
  color: #ffffff;
  cursor:pointer;
  background-image: url(./image/menuitemsel.png);
  background-repeat: no-repeat;
}
.menu-item-label {
  position:absolute;
  top:14px;
  width:100%;
  text-align: center;
  cursor:pointer;
}
</style>
<script type="text/javascript" src="./js/menu.js" ></script>
<div style="position:relative; left:0px; top:0px;"><image src="./image/galleria_ci.png"/></div>
<div style="position:relative; left:375px; top:-61px;" class="<? echo $menuitem == 1 ? 'menu-item-sel' : 'menu-item'?>" onclick="onMenuSelect(1)"><div class="menu-item-label">컨텐츠관리</div></div>
<div style="position:relative; left:520px; top:-109px;" class="<? echo $menuitem == 2 ? 'menu-item-sel' : 'menu-item'?>" onclick="onMenuSelect(2)"><div class="menu-item-label">스케줄관리</div></div>
<div style="position:relative; left:665px; top:-157px;" class="<? echo $menuitem == 3 ? 'menu-item-sel' : 'menu-item'?>" onclick="onMenuSelect(3)"><div class="menu-item-label">서비스관리</div></div>
<div style="position:relative; left:810px; top:-205px;" class="<? echo $menuitem == 4 ? 'menu-item-sel' : 'menu-item'?>" onclick="onMenuSelect(4)"><div class="menu-item-label">관리자정보수정</div></div>

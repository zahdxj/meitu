/**
 * 
 * @authors pan
 * @date    2016-11-19 17:10:48
 * group
 */

/* index */

/* access */

/* add */
function chkname(obj){
	if(obj.value==""){
		$("#sp1").html("用户组名不能为空");
	}else{
        $("#sp1").html("");
    }
}

/* addNode */

/* auth */

/* common */
function checkform(){
	if (page == 'add') {
		if(roleform.title.value==""){
			$("#sp1").html("用户组名不能为空");
			return false;
		}else{
	        $("#sp1").html("");
	    }
		return true;
	} else if (page == 'addNode') {
		if(nodeform.title.value==""){
			$("#sp1").html("节点名称不能为空");
			return false;
		}else{
            $("#sp1").html("");
        }
		if(nodeform.name.value==""){
			$("#sp2").html("节点标识不能为空");
			return false;
		}else{
            $("#sp2").html("");
        }
		if(nodeform.group.value==""){
			$("#sp3").html("分组不能为空");
			return false;
		}else{
            $("#sp3").html("");
        }
		return true;
	}
}


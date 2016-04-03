function tabelqx() {
    $("#zcheck").click(function () {
        var check = $(this).is(":checked");
        if (check == true) {
            $(this).closest("table").find(".fetablecheckbox").prop("checked", true);
            $(this).closest("table").find(".fetablecheckbox").closest("span").addClass("checked");
        }
        else {
            $(this).closest("table").find(".fetablecheckbox").prop("checked", true);
            $(this).closest("table").find(".fetablecheckbox").closest("span").removeClass("checked");
        }
    })
}

function menuchushi() {
    var num = 0;
    var menua = $(".page-sidebar-menu").find("a");
    for (var i = 0; i < menua.length; i++) {
        var urlstr = window.location.toString();
        if (urlstr.indexOf(menua.eq(i).attr("href").toString()) >= 0) {
            num = i;
            break;
        }
    }
    menua.eq(num).closest("li").addClass("active")
        .closest("ul.sub-menu").parent("li").addClass("active")
            .find("span.arrow").addClass("open");
}

$(document).ready(function () {
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null)
            return unescape(r[2]);
        return null;
    }

    var nava = $(".nav-tabs a");
    var classid = GetQueryString("classid");
    if (classid != null) {
        $(".nav-tabs a[href$='classid=" + classid + "']").closest("li").addClass("active");
    } else {
        $(".nav-tabs a").eq(0).closest("li").addClass("active");
    }

})


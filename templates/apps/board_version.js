

<script>
    $(document).ready(function () {
        var cc = 0;
        $("#btnSubmitVersion").click(function () {
            

            var comment = $("#version_comment").val();
            if ($.trim(comment) == "") {
                warning("请填写版本描述");
                return;
            }
            var is_tag = $("#version_is_tag").prop("checked") ? "Y" : "N";
            var json = { action: "submit", app_id: "{{$appinfo.id}}", comment: comment, is_tag: is_tag };

            $("#btnSubmitVersion").attr("disabled", true);
            getJSON("{{$rootpath}}api/version", json, function (data) {
                if (data.code == 0) {
                    
                    var newtr = $('<tr >'
                            + '<td>' + (data.return.is_tag == "Y" ? '<i class="fa fa-star  text-warning"></i>' : "") + ' ' + data.return.version + '</td>'
                            + '<td>' + data.return.committed_date + '</td>'
                            + '<td>' + data.return.comment + '</td>'
                            + '<td><a href="javascript:rollback('+data.return.version+')">回滚</a></td>'
                            + '<td><a href="javascript:versiondownload('+data.return.version+')">下载</a></td>'
                            + '</tr>');

                    newtr.insertAfter("#versionTH");
                    info("提交成功");

                    $("#version_comment").val("");
                    $("#version_is_tag").prop("checked",false);
                } else {
                    warning(data.result);
                }
            }, function () {
                $("#btnSubmitVersion").attr("disabled", false);
            });
        });
    });
    function rollback(version) {
        warning("回滚将会<b>覆盖</b>当前的工作区文件，请问是否继续操作？<br /><br />同时回滚前会将当前版本先进行提交版本。", function () {
            var json = { action: "rollback", app_id: "{{$appinfo.id}}", version: version };
            getJSON("{{$rootpath}}api/version", json, function (data) {
                if (data.code == 0) {

                    location.reload();

                } else {
                    alert(data.result);
                }
            }, function () {
                $("#btnSubmitVersion").attr("disabled", false);
            });
        });
    }
    function versiondownload(version) {
        warning("请千万不要把版本文件直接覆盖，做好备份，有需覆盖！", function () {
            var url="/api/api?action=downloadversion&app_id={{$appinfo.id}}&version="+version;
			window.open(url);
        });
    }
</script>
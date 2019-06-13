layui.define(["form","jquery"],function(exports){
    var form = layui.form,
        $ = layui.jquery,
        Address = function(){};

    Address.prototype.provinces = function() {
        //加载省数据
        var proHtml = '',that = this;
        $.get("area",{code:'',type:1}, function (pro) {


            for (var i = 0; i < pro.length; i++) {
                proHtml += '<option value="' + pro[i].code + '">' + pro[i].name + '</option>';
            }
            //初始化省数据
            $("select[name=province]").append(proHtml);
            form.render();
            form.on('select(province)', function (proData) {


                $("select[name=area]").html('<option value="">请选择县/区</option>');
                var value = proData.value;




                if (value > 0) {
                    $.post('area',{code:value,type:2},function (val) {
                        //console.log(val.length) ;
                        that.citys(val) ;
                    },"json");
                    //that.citys(pro[$(this).index() - 1].childs);


                } else {
                    $("select[name=city]").attr("disabled", "disabled");
                }
            });
        },'json');
    }

    //加载市数据
    Address.prototype.citys = function(citys) {


        var cityHtml = '<option value="">请选择市</option>',that = this;
        for (var i = 0; i < citys.length; i++) {
            cityHtml += '<option value="' + citys[i].code + '">' + citys[i].name + '</option>';
        }
        $("select[name=city]").html(cityHtml).removeAttr("disabled");
        form.render();
        form.on('select(city)', function (cityData) {
            var value = cityData.value;
            if (value > 0) {
                $.post('area',{code:value,type:3},function (area) {
                    that.areas(area) ;
                },"json");
                //that.areas(citys[$(this).index() - 1].childs);
            } else {
                $("select[name=area]").attr("disabled", "disabled");
            }
        });
    }

    //加载县/区数据
    Address.prototype.areas = function(areas) {
        var areaHtml = '<option value="">请选择县/区</option>';
        for (var i = 0; i < areas.length; i++) {
            areaHtml += '<option value="' + areas[i].code + '">' + areas[i].name + '</option>';
        }
        $("select[name=area]").html(areaHtml).removeAttr("disabled");
        form.render();
    }

    var address = new Address();
    exports("address",function(){
        address.provinces();
    });
});
---------------------
    作者：shao_keke
来源：CSDN
原文：https://blog.csdn.net/m0_37584159/article/details/80415212
    版权声明：本文为博主原创文章，转载请附上博文链接！
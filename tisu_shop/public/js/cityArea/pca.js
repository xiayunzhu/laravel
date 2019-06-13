layui.use('form', function () {
    var form = layui.form;

    /**
     * 省市三级联动-修改版
     */
    (function ($) {

        var citys = cityData;
        var pca = {};

        pca.city = {};
        pca.area = {};

        pca.init = function (province, city, area, initprovince, initcity, initarea) {//jQuery选择器, 省-市-区
            //省份选择器
            if (!province || !$(province).length) return;
            //清空省份选择器内容
            $(province).html('');
            //开始赋值
            $(province).append('<option selected></option>');
            //遍历赋值
            for (var i in citys) {
                $(province).append('<option value= ' + citys[i].name + '>' + citys[i].name + '</option>');
                pca.city[citys[i].name] = citys[i].city;
            }

            //检测省份是否设置
            if (initprovince) {
                $(province).find('option[value="' + initprovince + '"]').attr('selected', true);
            } else {
                initprovince = $(province).attr('initValue');
                $(province).find('option[value="' + initprovince + '"]').attr('selected', true);

            }

            //渲染页面
            form.render('select');

            //城市选择器
            if (!city || !$(city).length) return;
            //渲染空数据
            pca.formRender(city);
            //监听事件
            form.on('select(province)', function (data) {
                pca.cityRender(city, data.value);
            });

            if (initcity) {
                pca.cityRender(city, initprovince);
                $(city).find('option[value="' + initcity + '"]').attr('selected', true);
            }else {
                initcity = $(city).attr('initValue');
                pca.cityRender(city, initprovince);
                $(city).find('option[value="' + initcity + '"]').attr('selected', true);
            }
            //渲染页面
            form.render('select');

            //区县选择器
            if (!area || !$(area).length) return;
            //渲染空数据
            pca.formRender(area);
            //监听事件
            form.on('select(city)', function (data) {
                pca.areaRender(area, data.value);
            });

            if (initarea) {
                pca.areaRender(area, initcity);
                $(area).find('option[value="' + initarea + '"]').attr('selected', true);
            }else {
                initarea = $(area).attr('initValue');
                pca.areaRender(area, initcity);
                $(area).find('option[value="' + initarea + '"]').attr('selected', true);
            }

            //渲染页面
            form.render('select');
        }

        pca.formRender = function (obj) {
            $(obj).html('');
            $(obj).append('<option></option>');
            form.render('select');
        }

        pca.cityRender = function (obj, data) {
            var city_select = pca.city[data];
            $(obj).html('');
            $(obj).append('<option></option>');
            if (city_select) {
                for (var i in city_select) {
                    $(obj).append('<option value= ' + city_select[i].name + '>' + city_select[i].name + '</option>');
                    pca.area[city_select[i].name] = city_select[i].area;
                }
            }
            form.render('select');
        }

        pca.areaRender = function (obj, data) {
            var area_select = pca.area[data];
            $(obj).html('');
            $(obj).append('<option></option>');
            if (area_select) {
                for (var i in area_select) {
                    $(obj).append('<option value= ' + area_select[i] + '>' + area_select[i] + '</option>');
                }
            }
            form.render('select');
        }

        window.pca = pca;
        return pca;
    })($);

    //省市三级联动-注册-默认值设置
    //             pca.init('select[name=province]', 'select[name=city]', 'select[name=area]', '浙江', '杭州市', '江干区');
});
<!-- 省市区组件 -->
<template>
    <div class="cityarea">
        <el-cascader :options="cityList" v-model="cityarea" @change="selectCity" :clearable="true" change-on-select
                     placeholder="请选择省/市/区"></el-cascader>
    </div>
</template>

<script>
    import Vue from 'vue';
    import cityData from './cityData.js';
    import {Cascader} from 'element-ui';

    Vue.use(Cascader);

    export default {
        data() {
            return {};
        },

        props: ['value'],

        computed: {

            // 绑定数据
            cityarea: {
                get: function () {
                    return this.value ? this.value : [];
                    // return this.value;
                },
                set: function (val) {
                    return val;
                }
            },

            // 省市区数据
            cityList: function () {
                let data = [];

                cityData.forEach(prov => {
                    let obj = {};
                    obj.value = prov.name;
                    obj.label = prov.name;
                    obj.children = [];

                    prov.city.forEach(city => {
                        let obj2 = {};
                        obj2.value = city.name;
                        obj2.label = city.name;
                        obj2.children = [];

                        city.area.forEach(area => {
                            obj2.children.push({'value': area, 'label': area});
                        });
                        obj.children.push(obj2);


                    });
                    data.push(obj);


                });


                return data;
            }
        },

        methods: {
            // 选择省市区
            selectCity: function (val) {
                this.$emit('change', val);
            },

        }
    };
</script>

<style scoped>
    .cityarea {
        display: inline-block;
    }

    .cityarea .el-cascader {
        width: 100%;
    }
</style>
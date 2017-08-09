<template>
    <div>
        <multiselect
                tag-placeholder="创建新的标签"
                placeholder="搜索或添加标签"
                SelectLabel="按回车键选择"
                :label="label"
                :track-by="trackBy"
                :options="tags"
                :value="value"
                :multiple="true"
                :taggable="true"
                :max="max"
                @tag="putTag"
                @select="handleTagSelect"
                @remove="handleTagRemove"
        >
        </multiselect>
    </div>
</template>

<script>

    import Multiselect from 'vue-multiselect'
    export default{
        components: { Multiselect },
        data(){
            return {
                params : {
                    type : this.type
                },
                tags:[],
            }
        },
        created(){
            this.getTags();
        },
        props:{
            label:{
                type:String,
                default:'name'
            },
            trackBy:{
                type:String,
                default:'name'
            },
            value:{
                type:Array,
                default:null
            },
            max:{
                type:Number,
                default:3
            },
            type : {
                type : String,
                default :'articles'
            }

        },

        methods:{
            getTags(){
                window.axios.get('tags',{
                    params : this.params
                })
                    .then(response =>{
                        this.tags = response.data.data;
                    })
            },
            handleTagSelect(tag){
                this.value.push({
                    name:tag.name
                });
            },
            handleTagRemove(tag){
                let tags = this.value;
                for ( var t in tags){
                    if (tags[t].name == tag.name){
                        tags.splice(t,1);
                    }
                }
            },
            putTag(tag){
                this.value.push({
                    name:tag
                });
            },
        },
        watch :{
            type : function (type) {
                this.params.type = type;
                this.getTags();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
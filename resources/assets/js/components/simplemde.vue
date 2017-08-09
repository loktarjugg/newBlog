<template>
    <div>
        <textarea id="textarea"></textarea>
    </div>
</template>

<script>
    import SimpleMDE from 'simplemde';
    import '../plugs/inline_attachment/inline-attachment.min';
    import '../plugs/inline_attachment/codemirror-4.inline-attachment';
    export default{
        name: 'Simplemde',
        data(){
            return{}
        },
        props:{
            content : {
                type : String ,
                default : ''
            },
            actionUrl : {
                type :String,
                default : '/upload'
            },
        },
        mounted() {
            this.initialize();
        },
        methods : {
            initialize() {

                let configs = {
                    element : document.getElementById("textarea"),
                    initialValue : this.content,
                    lineWrapping : false,
                    parsingConfig : {
                        allowAtxHeaderWithoutSpace: true,
                        strikethrough: false,
                        underscoresBreakWords: true,
                    },
                    spellChecker:false,
                };

                // 实例化编辑器
                this.simplemde = new SimpleMDE(configs);

                require('simplemde/dist/simplemde.min.css');

                // 绑定输入事件
                this.simplemde.codemirror.on('change', () => {
                    this.$emit('change' , this.simplemde.value());
                });

                inlineAttachment.editors.codemirror4.attach(this.simplemde.codemirror, {
                    uploadUrl: this.actionUrl,
                    uploadFieldName:'file',
                    extraParams:{
                        'X-CSRF-TOKEN' : window.csrf_token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                });
            },
        }
    }
</script>
/**
 * Created by apple on 2017/8/3.
 */

export default {

    CascaderTags : state => {

        if (state.tags.hasOwnProperty('data')){
            let tags = state.tags.data;
            return tags.map(function (tag) {
                let data =  {
                    value : tag.id.toString(),
                    label : tag.name,
                };

                data.children = tag.child.map(tag => {
                   return {
                       value : tag.id.toString(),
                       label : tag.name
                   };
                });
                return data;
            });
        }
        return [];
    },
}

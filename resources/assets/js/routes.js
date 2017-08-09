/**
 * Created by apple on 2017/8/3.
 */
import Parent from './views/layouts/parent.vue'
export default [
    {
        path: '/admin',
        component: Parent,
        children: [
            {
                path: '/',
                component: require('./views/dashboard.vue'),
            },
            {
                path: 'articles', //通知
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/articles/index.vue'),
                        name: 'articles'
                    },
                    {
                        path : 'create',
                        component : require('./views/articles/create.vue'),
                        name : 'articles.create'
                    },
                    {
                        path : ':id/edit',
                        component : require('./views/articles/edit.vue'),
                        name : 'articles.edit'
                    },
                ]
            },
            {
                path : 'tags',
                component : Parent,
                children : [
                    {
                        path : '/',
                        component : require('./views/tags/index.vue'),
                        name : 'tags'
                    },
                ],
            },
            {
                path: '*',
                redirect: '/admin'
            }
        ]
    }

]
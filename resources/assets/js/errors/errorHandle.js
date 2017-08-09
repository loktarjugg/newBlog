/**
 * Created by apple on 2017/8/3.
 */

import { Notification } from 'element-ui';

/**
 * 错误处理
 * @param error
 */
export function errorHandle(error) {

    if (error.response.status === 422 ){
        let data = error.response.data;

        Object.values(data.data).map(function (key) {
            setTimeout(() => {
                Notification({
                    type:'error',
                    title:data.error,
                    message:key[0]
                })
            },500);
        });
    }else{
        Notification({
            type:'error',
            title: '错误',
            message: error.response.data.error
        })
    }

    console.log(error.response);

}
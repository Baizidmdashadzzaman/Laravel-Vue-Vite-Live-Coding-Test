import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')

const BuyerList = () => import('@/components/Buyer/BuyerList.vue')
const BuyerAdd = () => import('@/components/Buyer/BuyerAdd.vue')
const BuyerEdit = () => import('@/components/Buyer/BuyerEdit.vue')

const StyleList = () => import('@/components/Style/StyleList.vue')
const StyleAdd = () => import('@/components/Style/StyleAdd.vue')
const StyleEdit = () => import('@/components/Style/StyleEdit.vue')
const StyleAddItem = () => import('@/components/Style/StyleAddItem.vue')
/* Authenticated Component */


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    },
    {
        path:"/buyer/list",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"buyerlist",
                path: '/buyer/list',
                component: BuyerList,
                meta:{
                    title:`Buyer List`
                }
            }
        ]
    },
    {
        path:"/buyer/add",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"buyeradd",
                path: '/buyer/add',
                component: BuyerAdd,
                meta:{
                    title:`Buyer Add`
                }
            }
        ]
    },
    {
        path:"/buyer/edit",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"buyeredit",
                path: '/buyer/edit/:id',
                component: BuyerEdit,
                meta:{
                    title:`Buyer Edit`
                }
            }
        ]
    },
    {
        path:"/style/list",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"stylelist",
                path: '/style/list',
                component: StyleList,
                meta:{
                    title:`Style List`
                }
            }
        ]
    },
    {
        path:"/style/add",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"styleadd",
                path: '/style/add',
                component: StyleAdd,
                meta:{
                    title:`Style Add`
                }
            }
        ]
    },
    {
        path:"/style/edit",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"styleedit",
                path: '/style/edit/:id',
                component: StyleEdit,
                meta:{
                    title:`Style Edit`
                }
            }
        ]
    },
    {
        path:"/style/add-items",
        component:DahboardLayout,
        meta:{
            middleware:"auth"
        },
        children:[
            {
                name:"styleadditems",
                path: '/style/add-items/:id',
                component: StyleAddItem,
                meta:{
                    title:`Style Add Items`
                }
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router
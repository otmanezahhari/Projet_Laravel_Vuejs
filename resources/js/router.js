import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import DashboardPage from "./components/pages/DashboardPage"
import CryptoDetail from "./components/pages/CryptoDetail"
import Historique from "./components/pages/Historique"
import Portefeuille from "./components/pages/Portefeuille"


// Admin pages
// import CryptoList from "./admin/pages/CryptoListes"
import CryptoList from "./admin/pages/CryptoList"
import adminUsers from "./admin/pages/adminUsers"
import Login from "./admin/pages/Login"
import Role from "./admin/pages/Role"
import assignRole from "./admin/pages/assignRole"



const routes = [




    {
        path: '/login',
        component: Login,
        name: Login

    },

    {
        path: '/assignRole',
        component: assignRole,
        name: assignRole

    },
    {
        path: '/role',
        component: Role,
        name: Role

    },
    {
        path: '/adminUsers',
        component: adminUsers,
        name: adminUsers
    },
    {
        path: '/CryptoList',
        component: CryptoList,
        name: CryptoList
    },
    {
        path: '/DashboardPage',
        component: DashboardPage,
        name: DashboardPage
    },
    {
        path: '/crypto',
        component: CryptoDetail,
        name: CryptoDetail
    },
    {
        path: '/Portefeuille',
        component: Portefeuille,
        name: Portefeuille
    },
    {
        path: '/History',
        component: Historique,
        name: History
    },
]

export default new Router({
    mode: 'history',
    routes
})

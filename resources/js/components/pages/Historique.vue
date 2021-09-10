<template>
    <div>
        <div class="content">
            <div class="container-fluid">

                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <h1 class="title">Historique</h1>
                    <div class="_overflow _table_div">
                        <table class="_table">
                                <!-- TABLE TITLE -->
                            <tr>
								<th>Cryptos</th>
                                <th>Quantity</th>
                                <th>Achat/Vente</th>
								<th>Cours</th>
								<th>Date d'achats</th>
							</tr>

                            <!----- Items --->
                            	<!-- ITEMS  v-if="isDeletePermitted"-->
							<tr v-for="(his, i) in historique" :key="i" v-if="historique.length">

                                <td class="_table_name">{{his.coinName}}</td>
                                <td >{{his.quantity}}</td>
                                <td :class="his.actionType">{{his.actionType}}</td>
                                <td>{{his.cours}} €</td>
                                <td>{{his.created_at}}</td>
							</tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


</template>

<script>

export default {

    data(){
        return {
            historique : [],
            user:{},
            symbole:{
                DASH :'Dash',
                MIOTA : 'IOTA',
                XLM: 'Stellar',
                Ł : 'Litecoin',
                BCH : 'Bitcoin Cash',
                Ƀ : 'Bitcoin',
                XEM : 'NEM',
                ADA : 'Cardano',
                XRP :'Ripple',
                'Ξ' : 'Ethereum'
            },
            index: -1,
        }
    },
    async created(){

        this.user = this.$store.state.user

        const reslt = await this.callApi('get','app/get_action')

        if(reslt.status===200){

            this.historique = reslt.data.filter(elem=>elem.clientId == this.user.id)

        }else{
            this.swr();
        }
    },


}
</script>

<style scoped>
.title{
    text-align: center;
    padding-bottom: 15px;
}

.text-align-center{
    text-align: center;
}

.ACHAT{
    color: #89d289;
    font-weight: bold;
}

.VENTE{
    color: red;
    font-weight: bold;
}

</style>

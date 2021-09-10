<template>
    <div>
        <div class="content">
            <div class="container-fluid">

                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <h1 class="title">La liste des cryptos et leur cours</h1>
                    <div class="_overflow _table_div">
                        <table class="_table">
                                <!-- TABLE TITLE -->
                            <tr>
								<th>Cryptos</th>
                                <th>Prix</th>
								<th>HIGH24HOUR</th>
								<th>LOW24HOUR</th>
								<th>OPENDAY</th>
                                <th>24</th>
                                <th></th>
							</tr>

                            <!----- Items --->
                            	<!-- ITEMS  v-if="isDeletePermitted"-->
							<tr v-for="(crypto, i) in cryptos" :key="i" v-if="cryptos.length">
								<td class="_table_name">{{symbole[crypto.FROMSYMBOL]}} <span class="font-size-icon">({{crypto.FROMSYMBOL}})</span></td>
								<td>{{crypto.PRICE}}</td>
                                <td>{{crypto.HIGH24HOUR}}</td>
                                <td>{{crypto.LOW24HOUR}}</td>
                                <td>{{crypto.OPENDAY}}</td>
                                <td>{{crypto.CHANGEPCTDAY}}</td>
								<td class="text-align-center">

                                    <router-link  :to="{ path: 'crypto', query: { sym: crypto.FROMSYMBOL}}" class="ivu-btn"><Icon type="md-add" /> Détail</router-link>

								</td>
							</tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


</template>

<script>
import _ from 'underscore'

export default {

    data(){
        return {
            cryptos:[],
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
        const res = await this.callApi('get', 'app/get_category')

        if(res.status===200){
            let newCryptos = []
            res.data.map((elem)=> newCryptos.push(elem.iconImage.split('/')[2].split('.')[0]))
            const API_URL = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms='
                +newCryptos.join(',') + '&tsyms=EUR'

            this.axios.get(API_URL)
            .then(response =>{

                this.cryptos = _.map(response.data.DISPLAY, (value,key) =>{
                    return { FROMSYMBOL : value.EUR.FROMSYMBOL, PRICE: value.EUR.PRICE,HIGH24HOUR : value.EUR.HIGH24HOUR,LOW24HOUR : value.EUR.LOW24HOUR, OPENDAY : value.EUR.OPENDAY,CHANGEPCTDAY: value.EUR.CHANGEPCTDAY }
                })

            })

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
.btn{
    padding: 6px 20px;
    border-radius: 8px;
    background: #f8cccc;

}
.text-align-center{
    text-align: center;
}

.font-size-icon{
    font-size: 10px;
    font-weight: 400;
}
</style>

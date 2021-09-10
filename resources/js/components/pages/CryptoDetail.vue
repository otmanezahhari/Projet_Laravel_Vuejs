<template>
    <div>
        <div class="content">
            <div class="container-fluid">

                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <router-link :to="{ path: '/DashboardPage'}" class="ivu-btn"> Back to Dashboard</router-link>

                    <h1 class="h2 title">Statistique des 20 dernières jours</h1>
                    <div class="row mt-5" v-if="OurCryptodata.length>0">
                        <div class="col">

                            <line-chart :chartData="OurCryptodata" label="otmane"></line-chart>
                        </div>
                    </div>

                    <div class="content-achat-vente">
                        <div class="box-achat-content">
                            <h2 class="title">Achat Cryptos</h2>

                            <div class="space">
                                <h3>Prix :  <span>{{data.cours}} €</span></h3>
                            </div>

                            <div class="space">
                                <InputNumber :min="1" v-model="data.quantity"></InputNumber>
                            </div>
                            <div class="space">
                                <h3>Total :  <span>{{(data.cours * data.quantity).toFixed(2)}} €</span></h3>
                            </div>
                            <div class="space">
                                <Button type="primary" @click="makeAchat" :disabled="isAchat" :loading="isAchat">{{isAchat ? 'Achat...' : 'Acheter'}}</Button>
                            </div>

                        </div>

                        <div class="box-vente-content">
                            <h2 class="title">Vente Cryptos</h2>

                            <div class="space">
                                <h3>Prix :  <span>{{data.cours}} €</span></h3>
                            </div>

                            <div class="space">
                                <h3>Crypto à vendre : <span>{{myPortfolioQuantity}} </span></h3>
                            </div>

                            <div class="space">
                                <h3>Total :  <span>{{(data.cours * myPortfolioQuantity).toFixed(2)}} €</span></h3>
                            </div>
                            <div class="space">
                                <Button type="primary" @click="makeVente" :disabled="isAchat" :loading="isAchat">Vendre</Button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</template>

<script>
import _ from 'underscore'
import LineChart from '../../components/LineChart.vue'
export default {
    components: {
        LineChart
    },

    data() {
        return{
            OurCryptodata : [],
            data:{
                clientId :null,
                coinName:'',
                quantity:1,
                actionType:'',
                cours:null,
                slug:'clientId',
                wallet : 0,
            },
            param : '',
            myPortfolioQuantity : 0,
            selectedSymbole : '',
            user: null,
            isAchat: false,
            symbole:{
                DASH :'DASH',
                MIOTA : 'MIOTA',
                XLM: 'XLM',
                Ł : 'LTC',
                BCH : 'BCH',
                Ƀ : 'BTC',
                XEM : 'XEM',
                ADA : 'ADA',
                XRP :'XRP',
                'Ξ' : 'ETH'
            },
        }
    },

    methods :{
       async makeAchat(){
            if(this.data.cours * this.data.quantity<this.user.wallet){
                this.data.actionType = 'ACHAT'
                const res = await this.callApi('post','app/set_action',this.data)
                if(res.status ==201){
                    this.s('Votre paiement a été traité avec succé  !')
                    const newdata = {
                        id : this.user.id,
                        wallet : this.user.wallet - this.data.cours * this.data.quantity
                    }
                    await this.callApi('post','app/update_wallet',newdata)
                    this.$router.push('DashboardPage')
                }else{
                    this.swr();
                }
            }else{
                this.swr();
            }
        },

        async makeVente(){
            if(this.data.cours * this.data.quantity<this.user.wallet){
                this.data.actionType = 'VENTE'
                this.data.quantity = this.myPortfolioQuantity
                const res = await this.callApi('post','app/set_action',this.data)
                if(res.status ==201){
                    this.s('Votre Vente a été Enregistré  !')
                    const newdata = {
                        id : this.user.id,
                        wallet : this.user.wallet + this.data.cours * this.myPortfolioQuantity
                    }
                    await this.callApi('post','app/update_wallet',newdata)
                    this.$router.push('DashboardPage')
                }else{
                    this.swr();
                }
            }else{
                console.log("nok")
                this.swr();
            }
        }
    },

    async created(){

        this.user = this.$store.state.user

        this.data.clientId = this.user.id

        this.param = this.$route.query.sym

        this.data.coinName = this.symbole[this.param]

        const historiqueDataURLAPI = "https://min-api.cryptocompare.com/data/v2/histoday?fsym="
                     + this.symbole[this.param]  + "&tsym=USD&limit=20"

        const symbolePrixURLAPI = "https://min-api.cryptocompare.com/data/price?fsym="+this.symbole[this.param] +"&tsyms=EUR"

        await this.axios.get(historiqueDataURLAPI)
            .then((response)=>{
                this.OurCryptodata = _.map(response.data.Data.Data, value => {
                    return {
                            date: this.$moment.unix(value.time).format('DD/MM/YYYY'),
                            high : value.high ,low : value.low,open : value.open}
                })
            })
        await this.axios.get(symbolePrixURLAPI)
            .then((response) =>{

                console.log("response",response.data)
                this.data.cours = response.data.EUR

            })

        let reslt = await this.callApi('get','app/get_action')

        reslt  = reslt.data.filter(elem=>elem.clientId == this.user.id)

        const SpecificData = reslt.filter((elem)=> elem.coinName ===this.symbole[this.param])

        for(let i =0; i<SpecificData.length;i++){

            console.log("spec" ,SpecificData )

           if(SpecificData[i].actionType =="ACHAT"){
               this.myPortfolioQuantity = this.myPortfolioQuantity + SpecificData[i].quantity

            }else{
                this.myPortfolioQuantity = this.myPortfolioQuantity - SpecificData[i].quantity
            }
        }
    }


}
</script>

<style scoped>

.title{
    text-align: center;
    margin-bottom: 20px;
}



.content-achat-vente{
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.box-achat-content,.box-vente-content{
    flex: 1 1 50%;
    width: 50%;
    max-width: 49%;
    text-align: center;
    margin-top: 50px;
    border: 1px solid rgb(15, 15, 15);
}

.box-vente-content .title,.box-achat-content .title{
    margin-top: 40px;
}
</style>






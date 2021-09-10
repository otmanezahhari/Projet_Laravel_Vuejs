<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <h1 class="title">Portefeuille</h1>
                    <div class="_overflow _table_div">
                        <table class="_table">
                                <!-- TABLE TITLE -->
                            <tr>
								<th>Cryptos</th>
                                <th>Quantity</th>
                                <th>Depense en €</th>
								<th>Action</th>
							</tr>

                            <!----- Items --->
                            	<!-- ITEMS  v-if="isDeletePermitted"-->
							<tr v-for="(his, i) in cryptos" :key="i" v-if="cryptos.length">
                                <td class="_table_name">{{his}}</td>
                                <td >{{getQuantity(his)}}</td>
                                <td >{{getValue(his)}} €</td>
                                <td class="text-align-center">

                                    <router-link  :to="{ path: 'crypto', query: { sym: his}}" class="ivu-btn"><Icon type="md-add" /> Détail</router-link>

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

export default {

    data(){
        return {
            historique : [],
            user:{},
            cryptos : [],
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
    methods:{
        getQuantity(crypto){
            let myPortfolioQuantity = 0
            for(let i =0; i<this.historique.length;i++){
                console.log(this.historique[i].coinName,crypto)

                if(this.historique[i].coinName == crypto){
                    if(this.historique[i].actionType == "ACHAT"){

                        myPortfolioQuantity = myPortfolioQuantity + this.historique[i].quantity

                    }else{
                        myPortfolioQuantity = myPortfolioQuantity - this.historique[i].quantity

                    }


                }
            }
            return myPortfolioQuantity
        },

        getValue(crypto){
            let coursTotal = 0
            for(let i =0; i<this.historique.length;i++){
                console.log(this.historique[i].coinName,crypto)
                if(this.historique[i].coinName == crypto){
                    if(this.historique[i].actionType == "ACHAT"){

                         coursTotal = coursTotal + parseFloat(this.historique[i].cours)*this.historique[i].quantity

                    }else{
                         coursTotal = coursTotal - parseFloat(this.historique[i].cours)*this.historique[i].quantity

                    }

                }
            }
            return coursTotal.toFixed(2)
        }
    },
    async created(){

        this.user = this.$store.state.user

        const reslt = await this.callApi('get','app/get_action')

        if(reslt.status===200){

            this.historique = reslt.data.filter(elem=>elem.clientId == this.user.id)

            let newArr = [];

            this.historique.map((elem)=>{
                newArr.push(elem.coinName)
            })
            this.cryptos = [...new Set(newArr)];
        }else{
            this.swr();
        }
    },


}


</script>


<style scoped>
.title{
    text-align: center;
    margin-bottom: 25px;
}

.flex{
    display: flex;
    flex-wrap: wrap;
    text-align: center;
    justify-content: center;
}

.justify-content-between{
    justify-content: space-between;
}

.box-content-portffeuille{
    flex: 1 1 25%;
    width: 25%;
    max-width: 250px;
    background: rgb(226 255 245);
    padding: 22px 16px;
    margin: 0 40px 25px 0;
}

.box-content-portffeuille .box-img{
    width: 35px;
    height: 35px;
    margin-right: 8px;
}
.box-content-portffeuille .box-img img{
    width: 100%;
    height: 100%;
}

.box-content-portffeuille .bold{
    font-weight: bold;
}



.box-content-portffeuille .bitcoin-value span{
    display: block;
}
</style>




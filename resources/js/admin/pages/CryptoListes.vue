<template>
    <div>
        <div class="content">
            <div class="container-fluid">

                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Tags <Button @click="addModal=true"  ><Icon type="md-add"  /> Add Crypto</Button></p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                                <!-- TABLE TITLE -->
                            <tr>
								<th>ID</th>
								<th>Crypto name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>

                            <!----- Items --->
                            	<!-- ITEMS  v-if="isDeletePermitted"-->
							<tr v-for="(tag, i) in cryptos" :key="i" v-if="cryptos.length">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(tag,i)"  >Edit</Button>
									<Button type="error" size="small" @click="showDeletingModal(tag, i)"  :loading="tag.isDeleting" >Delete</Button>

								</td>
							</tr>
                        </table>
                    </div>
                </div>

                <!-- tag adding modal -->
				<Modal
					v-model="addModal"
					title="Add Crypto"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="data.tagName" placeholder="Add tag name"/>

					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addCrypto" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add tag'}}</Button>
					</div>

				</Modal>

                <!-- tag Editing modal -->
				<Modal
					v-model="editModal"
					title="Add Crypto"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="editData.tagName" placeholder="Edit tag name"/>

					<div slot="footer">
						<Button type="default" @click="editModal=false">Close</Button>
						<Button type="primary" @click="editCrypto" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit tag'}}</Button>
					</div>

				</Modal>

                <!-- delete alert modal -->
                <deleteModal></deleteModal>

            </div>
        </div>
    </div>


</template>

<script>
import deleteModal from '../components/deleteModal.vue'
import { mapGetters } from 'vuex'

export default {

    data(){
        return {
            data :{
                tagName : ''
            },
            addModal: false,
            editModal:false,
            isAdding: false,
            cryptos:[],
            editData : {
                tagName : ''
            },
            index: -1,
            showDeleteModal : false,
            isDeleing : false,
            deleteItem: {},
            deletingIndex: -1,
        }
    },
    methods: {
        async addCrypto(){
            if(this.data.tagName.trim()=='') return this.e('Tag Name is required')
            const res = await this.callApi('post','app/create_crypto',this.data)
            if(res.status ===201){
                this.cryptos.unshift(res.data)
                this.s('Tag has been added successfully !')
                this.addModal = false
                this.data.tagName = ''
            }else{
                if(res.status === 422) {
                    if(res.data.errors.tagName){
                        this.i(res.data.errors.tagName[0])
                    }

                }else{
                    this.swr()
                }
            }
        },
        async editCrypto(){
            if(this.editData.tagName.trim()=='') return this.e('Tag Name is required')
            const res = await this.callApi('post','app/edit_crypto',this.editData)
            if(res.status ===200){
                this.cryptos[this.index].tagName = this.editData.tagName
                this.s('Tag has been edited successfully !')
                this.editModal = false
            }else{
                if(res.status === 422) {
                    if(res.data.errors.tagName){
                        this.i(res.data.errors.tagName[0])
                    }

                }else{
                    this.swr()
                }
            }
        },

        showEditModal(crypto, index){
            let obj = {
                id: crypto.id,
                tagName : crypto.tagName
            }
            this.editData = obj
            this.editModal =true
            this.index = index
        },
        async deleteCrypto() {
            this.isDeleing = true

            const res = await this.callApi('post', 'app/delete_crypto',this.deleteItem)
            if(res.status===200){
                this.cryptos.splice(this.deletingIndex,1)
                this.s('Tag has been deleted successfully')

            }else{
                this.swrl()
            }
            this.isDeleing = false
            this.showDeleteModal = false
        },
        showDeletingModal(tag, id){
            const deleteModalObj  =  {
				showDeleteModal: true,
				deleteUrl : 'app/delete_crypto',
				data : tag,
				deletingIndex: id,
				isDeleted : false,
			}
            this.$store.commit('setDeletingModalObj', deleteModalObj)

        }


    },
    async created(){
        const res = await this.callApi('get', 'app/get_crypto')

        if(res.status===200){
            this.cryptos = res.data;
        }else{
            this.swr();
        }
    },
    components : {
		deleteModal
	},
    computed : {
		...mapGetters(['getDeleteModalObj'])
	},
    watch : {
		getDeleteModalObj(obj){
			if(obj.isDeleted){
                
				this.cryptos.splice(obj.deletingIndex,1)
			}
		}
	}
}
</script>

<template>
    <div>
        <div class="content">
            <div class="container-fluid">

                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Admins <Button @click="addModal=true"><Icon type="md-add" /> Add Admins</Button></p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                                <!-- TABLE TITLE -->
                            <tr>
								<th>ID</th>
								<th>User name</th>
								<th>Email</th>
                                <th>User Type</th>
                                <th>Created at</th>
								<th>Action</th>
							</tr>

                            <!----- Items --->

							<tr v-for="(user, i) in users" :key="i" v-if="users.length">
								<td>{{user.id}}</td>
								<td class="_table_name">{{user.fullName}}</td>
                                <td class="">{{user.email}}</td>
                                <td class="">{{getRole(user.role_id)}}</td>
								<td>{{user.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(user,i)">Edit</Button>
									<Button type="error" size="small" @click="showDeletingModal(user, i)"  :loading="user.isDeleting" >Delete</Button>

								</td>
							</tr>
                        </table>
                    </div>
                </div>

                <!-- tag adding modal -->
				<Modal
					v-model="addModal"
					title="Add Admin"
					:mask-closable="false"
					:closable="false"

					>
                    <div class="space">
					    <Input type="text" v-model="data.fullName" placeholder="Full name"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password"/>
                    </div>
                    <div class="space">
                        <Select v-model="data.role_id"  placeholder="Select admin type">
                            <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                        </Select>
                    </div>

					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Admin'}}</Button>
					</div>

				</Modal>

                <!-- tag Editing modal -->
				<Modal
					v-model="editModal"
					title="Edit users"
					:mask-closable="false"
					:closable="false"

					>
                    <div class="space">
					    <Input type="text" v-model="editData.fullName" placeholder="Full name"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="editData.email" placeholder="Email"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password"/>
                    </div>
                    <div class="space">
                        <Select   v-model="editData.role_id"  placeholder="Select admin type">
                            <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                            <!-- <Option value="Admin" >Admin</Option>
                            <Option value="Editor" >Editor</Option> -->
                        </Select>
                    </div>

					<div slot="footer">
						<Button type="default" @click="editModal=false">Close</Button>
						<Button type="primary" @click="editAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit admin'}}</Button>
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
            data : {
				fullName: '',
				email: '',
				password: '',
                userType:'',
				role_id: 0,
			},
            addModal: false,
            editModal:false,
            isAdding: false,
            users:[],
            editData : {
                fullName: '',
				email: '',
				password: '',
				role_id: null
            },
            index: -1,
            showDeleteModal : false,
            isDeleing : false,
            deleteItem: {},
            deletingIndex: -1,
            roles:[],
        }
    },
    methods: {
        async addAdmin(){
            if(this.data.fullName.trim()=='') return this.e('Full Name is required')
            if(this.data.email.trim()=='') return this.e('Email is required')
            if(this.data.password.trim()=='') return this.e('Password is required')
            if(!this.data.role_id) return this.e('role type is required')

            const res = await this.callApi('post','app/create_user',this.data)

            if(res.status ===201){
                this.users.unshift(res.data)
                this.s('Admin user has been added successfully !')
                this.addModal = false
                this.data.tagName = ''
            }else{
                if(res.status === 422) {
                    for(let i in res.data.errors){
                        this.e(res.data.errors[i][0])
                    }

                }else{
                    this.swr()
                }
            }
        },
        async editAdmin(){
            if(this.editData.fullName.trim()=='') return this.e('Full Name is required')
            if(this.editData.email.trim()=='') return this.e('Email is required')
            if(!this.editData.role_id) return this.e('User type is required')

            const res = await this.callApi('post','app/edit_user',this.editData)



            if(res.status ===200){
                this.users[this.index]= this.editData
                this.s('User has been edited successfully !')
                this.editModal = false
            }else{
                if(res.status === 422) {
                    for(let i in res.data.errors){
                        this.e(res.data.errors[i][0])
                    }

                }else{
                    this.swr()
                }
            }
        },

        showEditModal(user, index){
            let obj = {
                id: user.id,
                fullName : user.fullName,
                email : user.email,
                userType : user.userType,
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
        },

        getRole(roleid){
            let getRole = this.roles.filter((role)=> role.id == roleid)[0]
            if(getRole){
                return getRole.roleName

            }
        }


    },
    async created(){

        const [res, resRole] = await Promise.all([
            this.callApi('get', 'app/get_user'),
            this.callApi('get', 'app/get_roles')
        ])



        if(res.status===200){
            this.users = res.data;
        }else{
            this.swr();
        }

        if(resRole.status===200){
            this.roles = resRole.data;
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

				this.users.splice(obj.deletingIndex,1)
			}
		}
	}
}
</script>

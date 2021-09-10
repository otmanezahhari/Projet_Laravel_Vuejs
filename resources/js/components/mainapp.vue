<template>
    <div>

      <div v-if="$store.state.user" >
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu" >
        <div class="_1side_menu_logo">
          <h3 style="text-align:center;">Bitchest</h3>
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content">
          <div class="_1side_menu_img_name">
            <p class="_1side_menu_name Capitalize">{{this.user.fullName}}</p>
          </div>

          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">
              <li v-for="(menuItem, i) in permission" :key="i" v-if="permission.length && menuItem.read">
                <router-link :to="'/'+menuItem.name"><Icon type="ios-speedometer" /> {{menuItem.resourceName}}</router-link>
              </li>
              <!-- <li><a href="/assignRole"><Icon type="ios-speedometer" /> assignRole</a></li> -->

              <li><a href="/logout"><Icon type="ios-speedometer" /> Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header">
        <div class="_2menu _box_shadow justify-content-between">
          <div class="_2menu_logo">
            <ul class="open_button">
            </ul>
          </div>

          <div class="_2menu_logo">
            <h3>Balance: <span>{{this.user.wallet}} €</span></h3>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
    	<router-view/>
    </div>
</template>


<script>

export default{
    props: ['user', 'permission'],
    data(){
        return {
             isLoggedIn : false,
        }
    },

    created(){
       this.$store.commit('setUpdateUser', this.user)
       this.$store.commit('setUserPermission', this.permission)
    },

}

</script>

<style scoped>
.Capitalize{
    text-transform: capitalize;
}

.justify-content-between{
    justify-content: space-between;
}
</style>

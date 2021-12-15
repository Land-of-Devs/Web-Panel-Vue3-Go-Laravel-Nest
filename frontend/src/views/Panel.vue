<template>
  <div class="panel">
    <va-sidebar text-color="white" color="primary" class="panel-sidebar" gradient>
      <va-sidebar-item>
        <va-sidebar-item-content>
          <va-sidebar-item-title class="sidebar-title">
            Panel Admin
          </va-sidebar-item-title>
        </va-sidebar-item-content>
      </va-sidebar-item>

      <va-sidebar-item :to="{name: 'Panel.Dashboard'}">
        <va-sidebar-item-content>
          <va-icon name="dashboard"/>
          <va-sidebar-item-title>
            Dashboard
          </va-sidebar-item-title>
        </va-sidebar-item-content>
      </va-sidebar-item>

      <va-sidebar-item :to="{name: 'Panel.Products'}">
        <va-sidebar-item-content>
          <va-icon name="category"/>
          <va-sidebar-item-title>
            Products
          </va-sidebar-item-title>
        </va-sidebar-item-content>
      </va-sidebar-item>

      <va-sidebar-item :to="{name: 'Panel.Users'}" v-if="role >= 3">
        <va-sidebar-item-content>
          <va-icon name="person"/>
          <va-sidebar-item-title>
            Users
          </va-sidebar-item-title>
        </va-sidebar-item-content>
      </va-sidebar-item>


      <va-sidebar-item to="/">
        <va-sidebar-item-content>
          <va-icon name="meeting_room"></va-icon>
        </va-sidebar-item-content>
      </va-sidebar-item>

    </va-sidebar>
    <div class="panel-body">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex'
export default {
  setup() {
    
    const store = useStore();
    const role = computed(() => store.getters['user/getRole']);

    return {
      role
    }
  },
}
</script>

<style lang="scss" scoped>

.panel {
  display: flex;
  height: 100%;
}

.panel-sidebar {
  height: 100%;

  .sidebar-title {
    font-size: 15pt;
    font-weight: bold;
  }

  .back {
    margin-top: auto;
  }
}

.panel-body {
  flex-grow: 1;
}

</style>

import * as userService from '../services/users';
import { ref, watch, reactive } from 'vue';

export function useUsers(type) {

    //------[ VARS ]------\\
    const users = ref([]);
    const usersCount = ref(0);
    const details = reactive({ user: {}, new: {} });
    const page = ref(1);
    const search = ref('');
    const totalPages = ref(1);
    const loading = ref(false);

    //------[ PRODUCT LIST ]------\\
    async function fetchUsers() {

        users.value = [];
        let responsePromise = await userService.usersList(page.value, search.value !== '' ? search.value : null);
        if (responsePromise !== null) {
            const response = responsePromise;
            console.log(response)
            users.value = response.users;
            usersCount.value = response.pager.totalUsers;
            totalPages.value = response.pager.totalPages;
            console.log(totalPages.value)
            loading.value = false;
        } else {
            throw new Error(`Nothing was found!`);
        }
    }

    //------[ ACTIONS ]------\\

    //Update
    const updateUser = async (id, form) => {
        let updatedUser = await userService.update(id, form);
        if (updatedUser) {
            let index = users.value.findIndex(i => i.id == id);
            users.value[index] = updatedUser.user;
            Object.assign(details.user, updatedUser.user);
        }
    };

    //Create
    const createUser = async (form) => {
        let newUser = await userService.create(form);
        if (newUser) {
            users.value.unshift(newUser.user);
        }
    }

    const verifyUsers = async (indexs) => {
        await userService.verify({ uuids: indexs });
        newData();
    }

    const deleteUsers = async (indexs) => {
        await userService.del({ uuids: indexs });
        newData();
    }

    //------[ WATCHERS AND FUNC ]------\\
    const newData = async () => {
        loading.value = true;
        if (page.value !== 1) changePage(1)
        else await fetchUsers()
    };

    const changePage = (num) => {
        page.value = num;
    };

    watch(type, newData);

    watch(page, fetchUsers);

    watch(search, newData);

    return {
        fetchUsers,
        users,
        usersCount,
        page,
        totalPages,
        changePage,
        createUser,
        updateUser,
        deleteUsers,
        details,
        search,
        verifyUsers
    };
}
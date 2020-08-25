<template>
    <div>
        <passport-clients></passport-clients>
        <passport-authorized-clients></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>

    </div>
</template>

<script>
import PassportClients from '@/js/components/passport/Clients.vue';
import PassportAuthorizedClients from '@/js/components/passport/AuthorizedClients.vue';
import PassportPersonalAccessTokens from '@/js/components/passport/PersonalAccessTokens.vue';
export default {
    components:{
        PassportClients,
        PassportAuthorizedClients,
        PassportPersonalAccessTokens
    }
}
</script>

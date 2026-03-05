# Entra ID (Azure AD) Auth Setup

This app supports optional Microsoft Entra ID sign-in while keeping local email/password auth.

## Feature toggle

Add these to your `.env`:

```env
ENTRA_ENABLED=true
ENTRA_TENANT_ID=your-tenant-id-or-common
ENTRA_CLIENT_ID=your-app-client-id
ENTRA_CLIENT_SECRET=your-app-client-secret
ENTRA_REDIRECT_URI=http://localhost/IM/auth/entra/callback
ENTRA_POST_LOGOUT_REDIRECT_URI=http://localhost/IM/login
ENTRA_SCOPES="openid profile email offline_access"
ENTRA_DEFAULT_ROLE=readonly
# Optional mapping: sourceClaimValue:localRoleKey pairs
# ENTRA_ROLE_MAP="Admin:superadmin,Reader:readonly,<group-guid>:readandwrite"
```

Set `ENTRA_ENABLED=false` (or unset) to keep localhost behavior unchanged with local auth only.

## Azure app registration checklist

1. Register app in Microsoft Entra ID.
2. Add redirect URI: `/auth/entra/callback`.
3. Add logout redirect URI: `/login`.
4. Enable ID tokens (for OpenID Connect).
5. Create client secret.
6. Configure app roles/groups if using role mapping.

## Database update

Run one of these once:

- `C:\xampp\php\php.exe spark migrate`
- or execute `add_entra_object_id_to_users.sql`

If your DB already has tables but migrations history is not aligned (e.g. "Table 'users' already exists"), use the SQL script option.

This adds `users.entra_object_id` for object-ID-first user linking.

## Flow summary

- Login redirect: `/auth/entra/login`
- Callback: `/auth/entra/callback`
- Token/ID validation: state + nonce + signature + issuer + audience
- User linking/provisioning:
  - match by `entra_object_id` first
  - fallback by email
  - create/update local user
- Role mapping:
  - from `roles`/`groups` claims + optional `ENTRA_ROLE_MAP`
  - persisted to `users.usertype`
- Session:
  - local session still used
  - `auth_provider=entra` set for SSO sessions
- Logout:
  - `/auth/logout` remains default
  - if session is Entra, redirects through Entra logout endpoint

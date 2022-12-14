# CheGuevaraLdap
use ldap-connection
* Read source and write your login code by yourself
* C# code must compile and upload to another server

Usage:
```php
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $username = \request()->username;
        $type     = (filter_var($username, FILTER_VALIDATE_EMAIL)) ? Controller::LOGIN_TYPE_EMAIL : Controller::LOGIN_TYPE_USERNAME;

        $us = DB::selectOne("SELECT * FROM users WHERE {$type} = :username", ['username' => $username]);

        $credential = [
            $type      => $username,
            'password' => \request()->password
        ];

        $token = auth()->guard('api')->attempt($credential, ['exp' => (auth()->guard('api')->factory()->getTTL() * 60) * 10]);

        if(!$token AND isset($us->ldap_user)){
            if(Helper::cheGuevaraLdap($us->username, \request()->password)){
                $token = auth()->guard('api')->attempt($validator->validated());
            }
        }

        if(!$token){
            return self::response('error', \Illuminate\Http\Response::HTTP_OK);
        }

        $loginById = auth()->guard('web')->loginUsingId(auth()->guard('api')->id(), true);

        return self::response([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => auth()->guard('api')->user(),
            'expires_in'   => (auth()->guard('api')->factory()->getTTL() * 60) * 10,
            'roles'        => auth()->guard('api')->user()->getRoleNames(),
            'permissions'  => auth()->guard('api')->user()->getPermissionsViaRoles()->pluck('name'),
            'loginById' => isset($loginById) ? $loginById : null
        ], \Illuminate\Http\Response::HTTP_OK);

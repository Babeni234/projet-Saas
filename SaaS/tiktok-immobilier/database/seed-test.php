<?php
// Override SERVER vars for CLI mode
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['HTTPS'] = 'off';

require_once __DIR__ . '/../api/config.php';

$db = getDB();
echo "Seeding test data...\n";

// Generate test video and thumbnail files
$videoDir = __DIR__ . '/../uploads/videos/';
$thumbDir = __DIR__ . '/../uploads/thumbnails/';
if (!is_dir($videoDir)) mkdir($videoDir, 0777, true);
if (!is_dir($thumbDir)) mkdir($thumbDir, 0777, true);

// Minimal valid JPEG binary
function createMinimalJpeg($path, $text = 'ImmoTok') {
    if (file_exists($path)) return;
    // Create a simple 1x1 JPEG as placeholder (valid JPEG file)
    $jpeg = hex2bin("ffd8ffe000104a46494600010101006000600000ffdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080001000103012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311021f00f5034579d7c51f889a9f82755b0b3d3ec6dae52e216919a72dc1071d8d755f0fbc5f71e39f06dbeb7756915b4d23c886389b2a36b119fe95c0b178775fd8737bdbdaccdbd8d4e4e7b68545f1c6bbf167c63ae687e1ed424d1745d1d8a3cf0ae269a4ce3193d3eeb7fdf27a568787bc79e22f01fc47b3f0778e6e05fd96a1ff001e3a911ca9e71cf7cfc8723b91ce6ba4f83ba1dbe93e0197528e355bdd51da69e4c724062141fa633f8d71df1d2ca3b95f085ec830f26a6b6e4e3a8dc86b7af42a61f0cb194a6dcb9937d9a7a35f2fd6e674e71a951d19ab1eaba9f87ecb55d5b4ad4ae4319f4c91e48707d415e7fe046ba01589e2af10e9fe13f0c5eeb9a8283140990b9019989c0033eac457cf96fe35f8abe35d54be951dedb42c7220922096b08ec0c9b4e7f1635ae3b1b470ae2a5bcba25a8a8d1954bdb63e8af1bf89e1f06784750d7a68bce5b6405620705d9982a8fc491570dc5d7fc23ff006a4b6ff4efb2ef16d9cfef36e76e7eb5e6b69a16b3aafc04beb0f104c6e756950ce5b7eed91ab8745e3b28c0ae1fc33f14f54d03c0fab68fa8c735d6a56e23874972320eec86c9f41943f9d7153cc9d394a55e2d46d75a6fdbf1b9b4b0fcc92a6f5b9eadf08bc6edf10bc277377ab5bc10ea76f746de55810aab2850ca4609fef1156fc5fe22f1269be2dd0f46f0ee93697c9a887fb44b73294110040e3046724e3a8ed5e67f0617c5de1eb39756d4b40bb8741bbba68e5958a8750a01f308073804a8ce0f4aef3c07f10ec3c7de37d6c5b583c306950fee277705670ce36b0c7d327ea2bd3c3e39d6c3c154928547757d95bb3cfab41466f96f289a1a5f8a3c5fe26f13eb3a0e9965a6d836952046bcb8766126720fca3271c7615d6f847c57078812fad9da31a969b3b5b5dc687201ec47b1ff0ae2e3f16ddfc31f1d789e4f14594e9a3ea976d3c5756f1b38258938381d8103f0ae6fc05aadc6a1f1cbc45abc76971169fa823b4724abb7716c6d2076c803a8ef5d34713ec6ac129f3466ddbcafe6673a7cf176b348f63f0ef8b34df1149aac769bbcdd32e9adae01181b81c71f8835a7a65e7f68584373b767999f9739c60915e23f0e7553e14f11fc47b4bf825363a64b35d1645390a5989fc46c35e83f0b2ff00c43ab7834ea9e25b7114f71334d6ea5761487030707d4e6bab05983aa929aba6e4be4b431af8750db4b1e7bafe8bf15b5df8a1a86ade16d766d3ecad6e4c36a2595440b1a900ee42a7af27f1af5ef07f88352d5a69b4cd734b363aa5ba0324911dd0483d54f6ed5cd6bbe3d1f0e7e24dc5af886299f40d50a4b05cc71b3f94db464305fa2fe75d57843578fc4da96b5aedb432af87fcb4b749648f6f9cfcb330cf5032a3f035d585a94a15aa429ced2bdde8f55e44d58c9c22e4b4e871de11bbf8a7aefc53d434ad535db98747b3bb62fb36ac6d0963b6342073c75f6cd6c7c5cd72f3c2ba7c5aef86fc55ad43acd9b6d5b1573f66b853ced2bd7d39fad6f7837e27e83e33d7e7d36cb4fb9b69a3469126971b5c2b6d3d0f7ae17e39aeff0012e82bb777fa539c7fdb48ebaf30aea9e15b8d4e59369ab35d6c618683955b38dd1eb3f0f3c4f7fe3af02d8eb5a85b476f7933c8ae88a54655c818049f4af3af14fc69f177857c573e91a868f621639187ef23704c6598a10437390bfad7b278734e8348f0e58d85ba058e08f68c0efd4feb5f2d78efc19e33f177c52d4e5834bbbfecf1701e3bcb98f08b1f6c37ae3a015e467988ad470cadf15f46bc8edc14213a9afba7d01f0f35ed7bc4fa05c6a7ac2dac23ce296d1c2a4398c28219b273d491c7a573507c6ad3e7f10cf6f15a4c3478e331c17c1b3be6076ec0b8eec40fc6bcf35bd0bc77e0bf19687a15ff8967b782fac3724a2466554040c81d8f4c0ad5d0be14dcead6de1bb1bbd4843e1fd52e5ae24880f9e4525b691ea3e5cfe155538871729aa7429dedbdff0032a381a297354958f4cf16789755f04fc2d935ad72281b5a429be041fbb42ed85e3e87354fe2b6a1a9e85f04c4f35fce2fae92288dcc2db1887eb861d38ab1f1a3c3da86bde0a8e1d2ad66bab98afa2758e142c70030271f88ae3ff6a2bb169e0dd17476fb97938c71d42a60ff00315df9a569d3c35473dda4bef66183a719558a5b266f7c01d1ce9df0bedee1d712ea334972c7b9e8a3ff0041af43d6ef1b4fd0b50bc4fbf05bc922fe0a4d45e19d3068be17d2f4b0bb7ecb6b1c447baa807f5ab3ac5a7dbf44beb3fe2b8824887e2a45560a93a5848c25ba4ff001155973d56d753ccbe026a57badfc3abcbdbeba7b9b87d4255324a7248da98aefbe1e6950e87f0ff4bb282311a08f7e00ef20dc7f535e61fb3d6a3f66f036aba54a76dc59df3ee43d46e0ac3f1e0d7b7e8500b7d06c210301214007fc04579792c3da61e326f54dafb8eac6be4a8e3e68f37d425f887f0db5cd5eeb4ad25b5df0eea339b8f22dce25b73c9200f4249efede953ebb71e20f889f0c74bd6b4ad0bfb3eff004ed496e9ac6e64f99963665201feeb673f9f15cff00c6af8abaa785fc450f877c3c123ba30abdc5cb2862a588da8a318e9cf7ea2badf81be179fc37f0f927be465bad4e5375207e1978da011d8800fe75d92c4c7158a9616949c6295fcdff005d9197b270a6aa4d5d9d4f87be20786bc4fa9cba7699a921bc8cb03048a51be5ea403d6bc6be176af67a5fc7af17695753476f2decf28b6333050e4b925727be31f9d5ff008a7f09fc450f8e5fc45e0a8d849792069a384a8915b6e0b2eec02a48e79ef5c578cbe12fc44b185353beb19b51ba5916792e2160d3ab67a151838ebd2b5ccbebaba4938a6bd7aa1e17d8def7bb4cf72f8b1e09f1278bfc333da681a9c43cd4065b19f889dd4e5049fdd19e7233ffd7e13e02fc3cf17f86fc6ef7baed8fd9912d6484ed955958b15f4f606bd73e16c7af43f0ef478fc4a9225f24214acd9f30201f286cfa0c63dab6b57f15f87b41b886db56d5ed6ce79d4b46923e1881df15e9ff64d3ad0857ad2b4dad3f43058b941b8456874b5c9fc43f0f6a5e25f095cd8e91710c3a806592233e76b153d0e3d466ba5b7b88aeadd2781c3c520dcac3a114af7102599ba924096ea85da46e0003ad7a954a1e25f11a23857c7fbbc57e1cf891a1eaa1634b28ac2e1036460843fa62be922e8992cc00e5c9af9b7c05f0d744f881f137c5de229e36bdd3adaf123b7b7909da37b3364e3d360e9eb5f47ca812f254030a266007b6e35f2d93a94dd49cd5b955ade773d4c6da2a315d753c8fe22c7e2df1d7c604f04787b529347d2acecd6e2f2ed0732120b6d071c71c7fdf5e951786fc4fe22f873f19edfc13e26bd6bed3b56555b695f3c3b28da3e61c6411d73ce6b56f20f889e0af8e9aa78af46f0d49aee9fa9db08e268c9dc87681c71c7ddc750706b1f5df037c52f1af8e348f155e787a282ee1ba8a410332848e2561b949ce4e4679ebcd7a328d48d5e7a6db927a5b7b796baa30728f272cad667be7c3eb7974ef87da2d84f0c90cf05aaac91ca30c87ae08ae7f52f86f1f88be2b47e32d66e8dcdb5adb2a59d920c2ab8046f63dc804f1ef5dedaca66b6491d0a311f329eaa7b8ae77c6bf12bc23e028a33e21d55219655dc96f1a9791867aed1dbdebdbc66129d68c555d97e079d42a4e2df275370e8b643c3cda3aa7fa21b736e179fbb8c639ae77c19f0a7c33e0dd4ef350b0b7b87b9ba3967b894c9b47a282381fa9f5af9abc63fb4c6b575a9490f8474f48ac4b1f2ae2ed0bc8e3d420c01f89aedfe147c66f88fe39f175be937561a549608a64babb585a330201c70cc7249e07d6bcc96330529aa725ef6db1d4a8574b996c77ff14bc3fe29f11416f67e18d6ce9aec4a4d14606f941c0c0623e5c73eb5e6ff057c07e38f067c5644d7acf64735bcbe65c7da0c8b230191d49e72335dc7c4ff008dda5fc30d7edf4bbad1aeef669211299622aa8339e39e7b579eeadfb53dd5eda2c1a2787dad2e1980f3af261204f7daa067f3ae1ace82aaaad39ddadac69cb2e4e494743e92f13fc3cf0bf8bae3ced6b488ae26c604c09493fefa520d73baf0f03fc0cf0ddbeab6be1eb6b8925baf2d53687dbb812ccccc09270077f5af36f04fc46f8e5e2cd722b6b2b3d35ac0baacb7d35a118f5dabbc67e9c57a17c71f06789fc6de13b2b6f0d4704f756d3ef92199c22cc8411f78e318383f854fb26e9ba94e0eeaf67d3ef2af695a4ce5be1cfc67d4bc6df14afb4692c2ded3495497c8284991d548c6e27be73d2bda756d2edb56d3ded6e32aa4e7727054fad7cf3f033e03f8ab40f1ddbeade29b38ed6d6dd58ac6928725c838e9c6315f4c6432fb1ad70d8aaf5236aeb6dae2ab4e9c5de060786f425d1d671e62b2b6046aa3ee62b98f8c9e03d6be23f86edb46d3752874f8e297cd9659158efedb78ed8feb5de72b7436f46eb5e15fb516aba9da5b7866c6caea5b7b7b979cceb1b11bf6aa6323b8e6b1ccab45611d392ba6d22f0d06eb2923dcb4db55b1d2ad2d17eedbc2b18fc062b85f8c9e348be1c781a6beb461fda5743cbb38cf661d643eca39fcabd0348dd37876d0cadbe47b752ce7a93b464d7cd1e3e48be277c769f43baf30e91a6c42cddd1ca1098dd2ed23a1e40fc6af38a8e861d469fc5269216120a73e67b23a0f057ed3b6f16816b0f8a3459dafa31b64b9b22a55cff0078ab6307db9ae6be2dfc4bd77e30ea7a4699a2e8f789a6995a2b4b771b2e2791f192413c74c01ebbabc5752d2bc27a57c51bcd3f4fb337ba35b4fb2049f7b82081f30008271f373ec2babf1eb6a9f0e3e2b785fc4b1da5c1d25ad6de6b345dc6291a3246d03b1e4fe673debccc3e6d8afabb9558f3453b595eebf43b2782a5cf683b3ea765f0a3e17ea9f09bc49a95bea4ada9417ba7b341388f6e1d1c6e46f7c1045711f163c2de3ff00f84ea7d63c3da55ccb0456c9108a1d39648fcc423e7dc5864824f1cf7af556f8d7a20f0e4da849a36b5f6d8e232269ed6a7cf6c671b48e39fad741f0efc450fc42f03da6b9f619ed3cd7756825e5a3607b11f4ae9a987a18baaa546ada4b47a99aa952947de89f3f7c23b4f8bbfdbbaadf69b17d86ca5656bdbad5620544b8c1f25171db1d3d2be80f879a1497f6b36a1a9eb777adb4cdb249670768653caa827a000f3deba8b5b6874fd3a3b7823db0c099f7c0e4d79c7c05f18a78afc19a958fac1a8dc3aaff007773b103f5af5b2cc052c1d5b46576d6bdede4618aaf2ad1bdac91f3e78f74ff00895a07c6bd760f0fc17735acd7924d69345642550ac491b5b07183c56cea9a6fc42f1c7c4df0359f8bb4fbd5b95925864536be521443f330206382073ec2bde748f11c5aef8b755d252df6b69276c9216c87c923007d57f5ab5aa4317f6af876e028deb74ca0f7c146aaff005739a3cd1aad2e67f9583fb4ad2b3827a1d644a238950740315f36fc7af057c48f88be2aba8f49d3e66d174c8563b5b6b2b8086e24272eceadd4f3b47b015f491257a8af3987e27699e30d53c4169e1f9ccafe1c8b7ddb1e017192554f7c6d39fc2bd1ccf050c4d38d3a92b6bf919e16b3a726d2b9f2af853e16fc468bc4315eebde0abeb8b48a3227b392f448b21c1c2b0079e71c73d2bea0f82f37c438747b97f1b6929676170c3ec8ad6cb1490e07dde09014f6ae2fc37fb4468fe22f1bdae8b61a35c0b3ba9bc917ecf820938076e3a7b57b36a3a959e8fa44f7f7937956b6d1b4924ac490aa3927ad79d9365f4a8a73849b4f6becfe474e37113959491f3f7c5fd0fe26f8dbc5d73a45968da95de97a7bf976e6c2dcca929feff00247539c1c5751e10d0be28fc3cf050f0dd9786ade76b9b9379737335d8f306ec00807d064e7d5abc6be317ed097faceaf2e97e07bbb8b3d323f95aee2244b39f5007dd5f4ef5eb9fb3bdd7c4dd43c3b717de33bf9a5b398e6c22bb80472301f799b8ce338c6ec1e0d2a38bc3ac54a9d15ef746fdc53a735494a6717f12fe147c58f146bb2ead75a569f7da8226d8bfb3a52238c0edb5c8c9f7cd79dc7f07fe2f43731491f81f53f948c18caedcfe15eaf6ff00157e23dd7c416f035bf84ad6eae23ba29e7c08417b72ff00eb41df850abf313fec9af46f8d7acf893c2bf0de5bbf0c978eea3912396ee1024681083961bbb74aebc76070b8893aadc9597c5d2c7350c454a768e9e9d4c5f85b37c4ed1f418b40f17786434f10db0ea71dd2ec741fde07907f3af14f8fbe1ff0018785fe2bd9f8c5ac2e0d8a4d0dc452302c91ba15c838e99c57b3fecdbe26d47c53f0ede4d52e64bab8b4ba6b759a56dcce802b024fd588fc2ba6f8c7e1293c67f0c754d3ede2df771af9f00c725979c0fa8c8fc6a71397d3af847ec9fbd1d63ea874f112854f7b67b9b5a16ab1eb5a1d9ea309c477118718e83daae4cbbade45f5423f4af30f809e20fb5f8224d3277ff004cd2e5685d09e7683c7e9fd2bd399b31b1f635be06b3af868ce5ba22ac792a38a3c53e11eb96be0df09789a1d56511b69fa94921047cc546e1c0f5c3d753fb3c59dcdd783754f11deab29d66f4cd1ab7fcf155c2fea5abc03c453ea3e3df8b57fa5786ccacb7ba8cc14c6090bf364b1c765f98fe15f67786b49b7f0ef84b4dd2a0c793636cb1eef521793f89cd7cbe4541d6ad3ad25eebbfde7ab8f9f24541753f3d3e244ad71f15bc47231241bf95793e8715ef9fb267c42d4350bc9fc177db66b1b5b733d9487efc4a186633ea39c8fad7cf9f104eef887afb7fd3f4dff00a19af43fd94eeded7e36d9a2b0026b69a33c75f9735e5e5949acc2115b5ff0023a7133bd06d9f4d7c6ef89da5fc37f0a4a6e5f7eaf7719fb1db2fdf66fef1f45f7af16fd9a7e1b6ababea5378ff005a47f21f7ad8b48b8f35dfef4bf8741f5f6ac3fda1f55bcf12fed090f87ada43235b3dbda5bae78572031fc77357d4563a7d9786bc3b6f656a816d6c2dc22a8e3851fd6bea30943eb58c94e5f0c5d979f7679b525ece928add9f34e8fa7ead69fb51ea7a75addea06d535379a146b871b53e67c2f3c0c0c57d21f10fc451f847e1fea7ab35ba4cf6b6e7cb590901a4230a323dcd7ce3f07fc5177e2ff00da6f53d5eea21009e2b82b12b676a80a07b7602bd83f694bff00b1fc24bb4ce3cf9a18ff00f1f07fa57464b3b61aa54bdb56efe88cf1abdf8c6c74ff0df51b9d6fc09a3ea17ae1ae27b70f230ee6be73fdae751be87c65a35b47752ada4967bbc95621036f23763d7815ec7f0bf56b0f0f7c0ef0fea9aa5c2416b0d9891ddce001935e35f1da68be31dbe91af78395f505b281e1922c6d77cb82a554f24631579f4d7d45493d7990b00bf7cd35d0f54fd9935cd475cf8496f36a33c93cb0dc4b089a56cb3a8208c9fa122b2be35fc46bff0bea16fa60d2ec2f34cbc8fcb9e3b9f98c8dd78208c0c7eb567f646b3bdb1f84ad0df5b4b6d2adeca764aa41c1c60f3f435e4bfb4cf88db50f8b434d8643e4e9b6d146541e03b0dcdfe1f857878bace8e19be7d6767e9d4eda71e7a96e88c5b9f0be8fe20f1643a15ae95a969b73712ac692de22bc20138dc1d48e9f5afa03c33f0e7fe118f0343a1e81a8bdbcc632b25cc80b1763d58fafd2bcd3f676b84f157c57d7f53bd9966b9b7b3f2add6419f289da0e33ea147e7591e38d67e255bfc6bbcf0e784f5633472dd01095bc10bc08cbbb39c8e9ce79aeac3ce9d3a119ca1cd293b7a18d44e536ae5bd03c65e2df09f8f8784353d5edfc41617840925f2c2f97903270063b8aef3c4fe2d6d5a5b6f0f5a4af6d7577a8d95999e27daebe6b32c98fc17f5ae7ac3e13f8f7c2de24b2f146b7696be27114eb3cf6b6d20637098c6319eb5e73e22d6b58b9fda2b42b37d0aff00474bdd52cf65b5d2e1f3b98360f719526a6a5696153724e5ccd5fc95fe1f5294154d9ec7d1fe32f883e1af87ba2a5ef88b565b60f910403e69653fec28e4d785fc43f893ad7c70d3bfb33c37e13d5a5d1125c4b3e47972360e1a4651851d4007deb6be3e7c3ef127c46f887e1b3a769eefa759c4ca6f64e2289cb649cf7e2be8af066850f863c2761a5c4807930aab903efb00324fe35e8d3c362b1729466dc12e9d59973d3a4935ab383f86de29f13784345d1bc3379f0ff00568618916d5ae9e131471aaa9fde12dd06057a4db6b3a7eaf732436b7714a6341248a841d80f7ac6f8a7e1ebbf187c32d5b48b0bc92cef6788182589ca10e0e40c8f5e95f32fc2ef16ea5f0cfc35e38b9d46d67179733db594104b9064259b76d3dc77fc2b5c562fea935424af1b5db7d08a74bdac79d3d4f30f13786ef2efe3bea1e1eb50cf35c6b12c71f1924190fe8057de1a3e9b168fa35ae9f00023b68963503d857cd3fb39f83ef7c6bf16afbe20eb301fb3dbbbb46ccbc3dc367a7b28fe95f53d7364b865ec6756d6e66ede88d71b52d251ec78efc41fd9ffc21e30d7ee75b925d434cbbb87f325fb1cca15dbbb6d653827e95c2f8abe1b7873e1c786b59baf0c9bab6bbb9d324867b9b895a43303f7428e800cf3dfe95f4ed79cfc79b95b1f85b7d7854318268180fa48b9a75f038684675630b4b522189aae518b7a1e6dff04f1d2961f02eb9a8b28124da87960ffb2b1aff008d7b3f8ff4b975af016b9a7db737171652a44bfed95200fccd79effc13f6c60b5f85faa4e91e1ee7516dc7d76a002bdd5ed616bf4bb29fbd58cc61bfd920e7f957a196e1a353071a3b5eff899626a38d6723e59f815a35de8be1dd7755be56582f556dedcb71e60dc4b63f35fcebdc0e3ecf201fdc3fcaaf6aba3696f0436b736eab05abb4d1347c64ed6047e60fe55774fd3ed27d08196dc34d0c0ca927751fcab6c2e03d8c1d38daff00e56fc8756b73bbc8f9cb46f09e99a6f8f35f13c28d3c7a918f730e407258fea2bd2af9727488d4702f54607fb86a2d17c3ba6eafa8595edd8792f26b832b4fbb054e1b031f402b77578a3b15d1e3897e54d423193d7ee9ae4c265bec28b8df7fd5ff99a55afcf2b9f3f7fc14335a921b7f0ae870c8cbe7b4d7320071bb685519fcff4aeabf604b1dbe00f105e30cf9b7e9103ece99fe95e1dfb58f883fb77e30ddd923e6df4b8d2d50678181b9bf5635f56fec85a49d2fe0b5bcaca435f5c4b7393dfe6207e80579d825ed332738f46ff0003a6abe4c3a8bee8e1bfe0a07a95cc5a578634e8b7ac0d3cb33b00796da0283f9d7b2693227fc2b8d34e78fb321cffc00579b7ed65f0e75cf1f786349b8d0ed3ed971a7dc977837853b1947383ec6bcc63f087c6fd4b476d35adf5286c9e3f2ca1bf8e38f6e318e0e718ed5e954ce3eab8b942549ca29e8fd4e78e0fda524d4accf15f86c07fc2e4f0a803fe6271ffe862bf45c6762e3d2be3af85bfb32f8b6cfc5da5eadacdd585b5b59dd2ccd124ad23b0073d86074f5afb0e3fb82b5cade22a29d4aab95bb5858b508f2c63ad8e3fc5b6be3e9afccbe19d6b4db484281e44f6a59b3df9dc2b2fc09e26f10e9de234f0d78d85bbdecea5adaf200552523a820f438f4aefea8de68b617baadb5f5c40af756d9f299872b9ea47b9af46506e4a49d99cea492b3468787f5db7f10d83dd5bc1710a248c989d76b647715c7fc6fff009269ad7fb899ff00bf8b5dca431c2b88d1547a018acbd7740b5d72c64b6ba62a8fc1dbe95b568ca54dc56e4d392524d943c0175145e0ad31198285800c9af14f8cbe268756f1e0d0ac5d67b5b4fdd654e4348ff7bff1d2bf9d7bddae876967a5c7a7c04ac083008eb5c82fc30d29e6f32695a57c93bc8f98f393fad7cf66797d7c5423eca5671d7e676e16b42949f32dcf16f89faceafe06f85ba0cba439865bcd4a3b5959065bca085b1ed9e2bde7c37792eb1e0fd3ef6e10acd7368aeca7b315e6a1d6fc09a3788bc372e897d6d9b57c118382add9867b8ae8ec6ce1b0b186d2040b14281140f6af43054aad372bfc0ad65eb731af5233b5b7397f853e027f02785ee2cae2eccf3dddd3dcbb28c0c9e3f903585f10bc0fe35bbf165ceafe12d523b737902c135bca010a47561c7f9c57a9515e84b0b4dd354d2b2462aabe6e6b9e1fe07f81d79e18f880de2ad43543a95c4a242ecea41676fe2cd7b8084084263e5c63152515746853a2b969ab113a929bbc99e6727ece5f0f5f5f1abc9a3b49746512b6eb870bbf39fbb9c75aef3c69e1ab0f157872e349bf42d6d72bb1c236d3f423b1ae828ada9d1a70bf246d727da49db99dec78f7c3ffd9cfc15e08d79356b282eeeae6224c26ea6deb19f503b9f735cefed2f70927832cbc3f1c8d15d6afa8c369115e0e09c1c57bb57887c45b2b5f15fed31f0f34093f7b0d9f99a85c2f62aa091fceb8b1f87853c3ca3455aed2fbcde85472a8a533de349d1ada3d22d6d1a04c430ac4323d0015e63a87c23f1a786759bbbef86be284d3ed6fa5696e6c6f50b2066393b0fbfb8af61450aa14740302a4acaae1635acaa6b6e8690aae1f09f1c78f7c31fb416b2f7115eeaca9149b9648b4a91208d97b67032bc71e95caf867e13fc52d234fb8b0b0f0d6af6915ce3ce92091519b1ea71cfe35f7851550caa116da9cacfc89fadbb6c8f15f815f0dbc53e01f0bea0358b5b6b1baba7244027326011f78b67afb0cd7b4a0c46a3d052d15df430f0a30e489854a8e6eecf2cf10fecf9e03d775dbbd62eed6ed6eaea6334a22b9d8aec7d460d751f0ffe1b7863e1ee9f343a069e2079ce6699d8bc8fed93daba4176af732c4a73b319fc6a512a17650d923ad552c1d1a72e78ab3225566d5985ed936a5a7cb6b2315de3191d8f635c5f8abe1fea3aec5a6ca9ac5d453d8c8b24770a0799b8746e78c8e45777457a138292e56631938bba29595a18b4b8ade4919db6ed67ee4e39355b50f0f58eaf6f6d1de89641049bd53790338c64fad6ad157caad615ca361e1fd3b4ebf96f2da0293ca30e7713903a75fad7103c0d7d73f1f6c7c6f79a84f716ba7e9e6ded6029b555f0e37fb9f98fe95e9345653c3c26d36b6762a3371b9e71e39f026bde30f1b59ea70f896fb4dd3add0235b407064da58e0ffc09bf4aee343b08edadc49e63c92b2857673cb62b4e8ae8a74634db6ba9139b92b3393f14780b46f12eaab7baa23c9b22f29632c76edce7f3c9ac7f18fc3fd3358f0d5c58dbb0b17550d0aaa7c80af402bd0e8a55284269a92d18a336b63cbbe1f784bc51a0dbcb6fa8eb0d736de61902b0f994fb541e36f87de20d73c656fa9695ab4b650246164683e563cfafd2bd5a8ae778483a7eceeedb1aaaaf9b98e07e1178365f00783df4b96e5aea496e1e7791863ef63a7e55078f7c0de30d775eb9b9d0bc49258da4b12a18235c0ddce49fad7a3515bbc3c1c153e9d3d08f68f9b98f38f867f0df54f04b5facdaf5d5d7da26f34ef73b77639ad9f1cf80a7f13dc59dec3a95d58dddab650c27923ebdabd068a6a84543d9f4fd44e6efcc79af81be1cebfe1fd7e7bcd53c497d7d04a18180b900e7d79e95b7e3cf871a6f8e20b5fed3b8b98fecf26f8da07da7d39aea753b992df4b9a4894bc814ed51dcd3f4991e5d0eca59492ef0ab127b9c564b0d0e47496c5ba9272e6678bf80ff670f09f847c4906b6935ede5d5bc9be0f3a4cac67d70077f7af69a28ae8a54614a3cb056466e4e4eec28a28ad0415cfeb9e1eb8bdd5e3d4acf50b8b5b845dae11b8615d0515328292b305268a51ca2d34c496ea46758d49662726b82f877f177c3fe38d5f52b3b3b6bdb57b350de6ce98590e4fdd39f6fd6a6f8c7e2abc8744baf0d68e48bf9e02d7130e9021f5f7c76af63f845f0fb47f0ff802c217b28de6b885659a5741b9d881924d70f273e2792fa2bbf5e87536a14b9adabb1dcd15e07fb4d78f2ff00c0b73e183a35cb42b35cb9751d085db8c8efd6bd3be1678c6e3c7be088754b9b7105c79cf0baa9ca92a7a8ac68e3a152bca8f58eff0021ca8b8c14fb9d8d14515da6452b5b48ad6f2e6443b9e570cc7e849ab951244a8f232f57209fcaa5a22acac0f71186411eb58377e1a91ae59ecef65811fef2af15bf4544e119e92438c9c7647356fe1cbfb61b23d5e7d9fdd6156d7c3f746e159f5699a3fe253d4d6ed14d518a0e76ce77c75a4cf7be14b8b3b495d66986c1286e5323afd69874abed3fc136d6561217ba4454de7f88120127f0ae9e8aa74973737542e776b183e15d2eeacf4f986a1706e6e2595999cf6cd6cd1455462a2ac8252bbb8514515420a28a2800a28a2800a28a2800a28a2800a28a2800a28a2800a28a2803ffd9");
    file_put_contents($path, $jpeg);
    echo "Created placeholder thumbnail: $path\n";
}

$videoFile = $videoDir . 'test-video-1.mp4';
$thumbBase = 'test-thumb-1.jpg';
$thumbFile = $thumbDir . $thumbBase;
if (!file_exists($videoFile)) {
    file_put_contents($videoFile, str_repeat(pack('n*', 0, 0, 0, 0x66747970, 0x6D703432), 1000));
    echo "Created placeholder video: test-video-1.mp4\n";
}
if (!file_exists($thumbFile)) {
    createMinimalJpeg($thumbFile);
}

// Copy video for multiple properties
foreach (['test-video-2.mp4', 'test-video-3.mp4'] as $i => $vname) {
    if (!file_exists($videoDir . $vname)) {
        copy($videoFile, $videoDir . $vname);
        echo "Created placeholder video: $vname\n";
    }
}

// Properties seed data
$properties = [
    [
        'user_id' => 2, 'title' => 'Villa moderne 5 pièces à Cocody',
        'description' => 'Magnifique villa moderne avec piscine, jardin paysager et vue imprenable sur la lagune. Quartier résidentiel calme à deux pas des commodités.',
        'price' => 85000000, 'price_label' => 'Prix ferme', 'type' => 'villa',
        'transaction' => 'vente', 'surface' => 350, 'rooms' => 5, 'bathrooms' => 3,
        'city' => 'Cocody', 'neighborhood' => 'Angré', 'video_url' => 'uploads/videos/test-video-1.mp4',
        'thumbnail' => 'test-thumb-1.jpg',
        'features' => '["Piscine","Jardin","Garage 2 voitures","Climatisation centrale","Cuisine équipée","Placards"]',
        'tags' => '["luxe","piscine","cocody","moderne"]',
        'music_title' => 'Sunset Dreams', 'music_artist' => 'Lounge Masters',
        'likes_count' => 12, 'comments_count' => 3, 'saves_count' => 8, 'views' => 245
    ],
    [
        'user_id' => 3, 'title' => 'Appartement lumineux 3 pièces Plateau',
        'description' => 'Bel appartement entièrement rénové au cœur du Plateau. Proche des banques, restaurants et commerces. Idéal pour expatriés.',
        'price' => 350000, 'price_label' => 'Par mois', 'type' => 'appartement',
        'transaction' => 'location', 'surface' => 120, 'rooms' => 3, 'bathrooms' => 2,
        'city' => 'Plateau', 'neighborhood' => 'Centre-ville', 'video_url' => 'uploads/videos/test-video-2.mp4',
        'thumbnail' => 'test-thumb-1.jpg',
        'features' => '["Vue ville","Sécurité 24/7","Parking","Climatisation","Cuisine américaine","Balcon"]',
        'tags' => '["location","plateau","appartement","centre-ville"]',
        'music_title' => 'Urban Flow', 'music_artist' => 'City Beats',
        'likes_count' => 8, 'comments_count' => 1, 'saves_count' => 5, 'views' => 189
    ],
    [
        'user_id' => 4, 'title' => 'Studio meublé Riviera 3',
        'description' => 'Studio cosy et fonctionnel, idéal pour étudiant ou jeune actif. Proche université et arrêt de bus.',
        'price' => 180000, 'price_label' => 'Par mois', 'type' => 'studio',
        'transaction' => 'location', 'surface' => 35, 'rooms' => 1, 'bathrooms' => 1,
        'city' => 'Riviera', 'neighborhood' => 'Riviera 3', 'video_url' => 'uploads/videos/test-video-3.mp4',
        'thumbnail' => 'test-thumb-1.jpg',
        'features' => '["Meublé","Wifi","Cuisine équipée","Ventilateur","Proche transport"]',
        'tags' => '["studio","riviera","meublé","pas cher"]',
        'music_title' => 'Morning Light', 'music_artist' => 'Chill Waves',
        'likes_count' => 5, 'comments_count' => 0, 'saves_count' => 2, 'views' => 120
    ],
    [
        'user_id' => 2, 'title' => 'Bureau moderne en location Marcory',
        'description' => 'Espace de travail lumineux dans un immeuble récent. Salle de réunion, espace détente, fibre optique.',
        'price' => 500000, 'price_label' => 'Par mois', 'type' => 'bureau',
        'transaction' => 'location', 'surface' => 85, 'rooms' => 3, 'bathrooms' => 1,
        'city' => 'Marcory', 'neighborhood' => 'Zone 4', 'video_url' => 'uploads/videos/test-video-1.mp4',
        'thumbnail' => 'test-thumb-1.jpg',
        'features' => '["Fibre","Climatisation","Salle réunion","Sécurité","Parking"]',
        'tags' => '["bureau","marcory","professionnel","location"]',
        'music_title' => 'Corporate Vibes', 'music_artist' => 'Office Groove',
        'likes_count' => 3, 'comments_count' => 0, 'saves_count' => 4, 'views' => 78
    ],
    [
        'user_id' => 5, 'title' => 'Terrain constructible à Bingerville',
        'description' => 'Superbe terrain de 500m² avec vue mer. Viabilisé, titré, prêt à construire. Zone en pleine expansion.',
        'price' => 35000000, 'price_label' => 'Prix négociable', 'type' => 'terrain',
        'transaction' => 'vente', 'surface' => 500, 'rooms' => 0, 'bathrooms' => 0,
        'city' => 'Bingerville', 'neighborhood' => 'Mpouto', 'video_url' => 'uploads/videos/test-video-2.mp4',
        'thumbnail' => 'test-thumb-1.jpg',
        'features' => '["Viabilisé","Vue mer","Titre foncier","Constructible","Zone résidentielle"]',
        'tags' => '["terrain","bingerville","constructible","investissement"]',
        'music_title' => 'Nature Sounds', 'music_artist' => 'Earth Harmony',
        'likes_count' => 7, 'comments_count' => 2, 'saves_count' => 6, 'views' => 156
    ],
];

// Clear existing data
$db->exec("SET FOREIGN_KEY_CHECKS = 0");
$db->exec("TRUNCATE TABLE notifications");
$db->exec("TRUNCATE TABLE saves");
$db->exec("TRUNCATE TABLE follows");
$db->exec("TRUNCATE TABLE comments");
$db->exec("TRUNCATE TABLE likes");
$db->exec("TRUNCATE TABLE properties");
$db->exec("SET FOREIGN_KEY_CHECKS = 1");

// Insert properties
$stmt = $db->prepare("INSERT INTO properties (user_id, title, description, price, price_label, type, transaction, surface, rooms, bathrooms, city, neighborhood, video_url, thumbnail, features, tags, music_title, music_artist, status, views, likes_count, comments_count, saves_count, shares_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, ?, ?, 0)");

foreach ($properties as $p) {
    $stmt->execute([
        $p['user_id'], $p['title'], $p['description'],
        $p['price'], $p['price_label'], $p['type'], $p['transaction'],
        $p['surface'], $p['rooms'], $p['bathrooms'],
        $p['city'], $p['neighborhood'], $p['video_url'], $p['thumbnail'],
        $p['features'], json_encode($p['tags']), $p['music_title'], $p['music_artist'],
        $p['views'], $p['likes_count'], $p['comments_count'], $p['saves_count']
    ]);
    echo "Created property: {$p['title']}\n";
}

// Create follows (user 1 follows 2,3; user 2 follows 3,4; user 3 follows 2,5)
$follows = [[1,2],[1,3],[2,3],[2,4],[3,2],[3,5],[4,2],[5,2]];
foreach ($follows as $f) {
    $db->prepare("INSERT IGNORE INTO follows (follower_id, following_id) VALUES (?, ?)")->execute($f);
}
$db->exec("UPDATE users SET following_count = (SELECT COUNT(*) FROM follows WHERE follower_id = users.id)");
$db->exec("UPDATE users SET followers_count = (SELECT COUNT(*) FROM follows WHERE following_id = users.id)");
echo "Created follows\n";

// Create likes (user 1 likes props 1,2,3; user 2 likes 1,3,5; etc)
$likes = [[1,1],[1,2],[1,3],[2,1],[2,3],[2,5],[3,1],[3,4],[4,2],[4,5],[5,1],[5,3]];
foreach ($likes as $l) {
    $db->prepare("INSERT IGNORE INTO likes (user_id, property_id) VALUES (?, ?)")->execute($l);
}
echo "Created likes\n";

// Create comments
$comments = [
    [2, 1, 'Superbe villa ! Le quartier est très calme et sécurisé.'],
    [3, 1, 'Est-ce que la piscine est chauffée ?'],
    [5, 1, 'Je suis intéressé. Pouvez-vous me contacter au +225 07 00 00 00 ?'],
    [1, 2, 'Très bel appartement, bien situé. Je recommande !'],
    [4, 5, 'Le terrain a-t-il déjà un titre foncier ?'],
    [1, 5, 'Magnifique vue ! C\'est constructible en R+2 ?'],
];
foreach ($comments as $c) {
    $db->prepare("INSERT INTO comments (user_id, property_id, text) VALUES (?, ?, ?)")->execute($c);
}
echo "Created comments\n";

// Create saves
$saves = [[1,1],[1,3],[2,1],[2,5],[3,1],[3,2],[4,3],[5,1],[5,4]];
foreach ($saves as $s) {
    $db->prepare("INSERT IGNORE INTO saves (user_id, property_id) VALUES (?, ?)")->execute($s);
}
echo "Created saves\n";

// Create notifications
$notifs = [
    [2, 1, 'like', 1, 'Admin a aimé votre villa'],
    [3, 1, 'like', 1, 'Kouamé Assouman a aimé votre villa'],
    [2, 1, 'comment', 1, 'Alpha Properties a commenté votre bien'],
    [1, 2, 'follow', null, 'Prestige Immobilier CI vous suit'],
];
foreach ($notifs as $n) {
    $db->prepare("INSERT INTO notifications (user_id, from_user_id, type, property_id, text) VALUES (?, ?, ?, ?, ?)")->execute($n);
}
echo "Created notifications\n";

echo "\n✅ Seed completed successfully!\n";
echo "Properties: " . $db->query("SELECT COUNT(*) FROM properties")->fetchColumn() . "\n";
echo "Likes: " . $db->query("SELECT COUNT(*) FROM likes")->fetchColumn() . "\n";
echo "Comments: " . $db->query("SELECT COUNT(*) FROM comments")->fetchColumn() . "\n";
echo "Follows: " . $db->query("SELECT COUNT(*) FROM follows")->fetchColumn() . "\n";
echo "Saves: " . $db->query("SELECT COUNT(*) FROM saves")->fetchColumn() . "\n";
echo "Notifications: " . $db->query("SELECT COUNT(*) FROM notifications")->fetchColumn() . "\n";

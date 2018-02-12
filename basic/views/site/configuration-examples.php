<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Configuration-examples';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10 ">
            <table class="table table-bordered table-hover table-custom">
                <thead>
                <tr>
                    <th><h3>Examples of configuration for operation system</h3></th>
                </tr>
                </thead>
                <tbody>
                <tr data-toggle="collapse" data-target='#ex-1'>
                    <td class="btn btn-primary btn-block btn-custom">Apple airport</td>
                </tr>
                <tr id="ex-1" class="collapse">
                    <td>
                        <p>Using the Airport Utility, choose your Airport device and go into the Manual Setup. Configure
                            the Advanced / IPv6 information as noted below, based on the information for this tunnel.

                            After setting up the tunnel, you may get a warning from the Airport that there's a problem
                            with the tunnel configuration. Ensure you have a IPv6 DNS server set in the TCP/IP settings
                            to correct the problem. Alternatively, if you can reach IPv6 websites with the error notice,
                            you should be able to safely tell the Airport to ignore the error.
                        </p>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-2'>
                    <td class="btn btn-primary btn-block btn-custom">Cisco IOS</td>
                </tr>
                <tr id="ex-2" class="collapse">
                    <td>
                        <pre>
                            configure terminal
                            interface Tunnel0
                                description NetAssist IPv6 Tunnel Broker
                                no ip address
                                ipv6 enable
                                ipv6 address <?= $data['ipv6_if_their'] . "/64\n"; ?>
                                tunnel source <?= long2ip($data_user->ip) . "\n"; ?>
                                tunnel destination 62.205.132.12
                                tunnel mode ipv6ip
                                ipv6 route ::/0 Tunnel0
                            end
                            write
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-3'>
                    <td class="btn btn-primary btn-block btn-custom">Fortigate 4.x</td>
                </tr>
                <tr id="ex-3" class="collapse">
                    <td>
                        <pre>
                            config system sit-tunnel
                                edit "NA"
                                    set destination 62.205.132.12
                                    set ip6 <?= $data['ipv6_if_their']."/64\n"; ?>
                                    set source <?= long2ip($data_user->ip)."\n"; ?>
                                next
                            end

                            config router static6
                                edit 1
                                    set device "NA"
                                next
                            end
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-4'>
                    <td class="btn btn-primary btn-block btn-custom">FreeBSD < 4.4</td>
                </tr>
                <tr id="ex-4" class="collapse">
                    <td>
                        <pre>
                            ifconfig gif0 <?= long2ip($data_user->ip); ?> 62.205.132.12
                            ifconfig gif0 inet6 <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> prefixlen 128
                            route -n add -inet6 default <?= $data['ipv6_if_our'] . "\n"; ?>
                            ifconfig gif0 up
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-5'>
                    <td class="btn btn-primary btn-block btn-custom">FreeBSD >= 4.4</td>
                </tr>
                <tr id="ex-5" class="collapse">
                    <td>
                        <pre>
                            ifconfig gif0 create
                            ifconfig gif0 tunnel <?= long2ip($data_user->ip); ?> 62.205.132.12
                            ifconfig gif0 inet6 <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> prefixlen 128
                            route -n add -inet6 default <?= $data['ipv6_if_our'] . "\n"; ?>
                            ifconfig gif0 up
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-6'>
                    <td class="btn btn-primary btn-block btn-custom">JunOS</td>
                </tr>
                <tr id="ex-6" class="collapse">
                    <td>
                        <pre>
                            interfaces {
                                ip-0/1/0 {
                                    unit 0 {
                                        tunnel {
                                            source <?= long2ip($data_user->ip) . ";\n"; ?>
                                            destination 62.205.132.12;
                                        }
                                        family inet6 {
                                            address <?= $data['ipv6_if_their']; ?>
                                        }
                                    }
                                }
                            }
                            routing-options {
                                rib inet6.0 {
                                    static {
                                        route ::/0 next-hop <?= $data['ipv6_if_our'] . ";\n"; ?>
                                    }
                                }
                            }
                            forwarding-options {
                                family {
                                    inet6 {
                                        mode packet-based;
                                    }
                                }
                            }
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-7'>
                    <td class="btn btn-primary btn-block btn-custom">JunOS ES</td>
                </tr>
                <tr id="ex-7" class="collapse">
                    <td>
                        <pre>
interfaces {
    ip-0/1/0 {
        unit 0 {
            tunnel {
                source <?= long2ip($data_user->ip).";\n"; ?>
                            destination 62.205.132.12;
            }
            family inet6 {
                address <?= $data['ipv6_if_their']."/64;\n"; ?>
                            }
        }
    }
}
routing-options {
    rib inet6.0 {
        static {
            route ::/0 next-hop <?= $data['ipv6_if_our'].";\n"; ?>
                            }
    }
}
security {
    forwarding-options {
        family {
            inet6 {
                mode packet-based;
            }
        }
    }
}                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-9'>
                    <td class="btn btn-primary btn-block btn-custom">Linux</td>
                </tr>
                <tr id="ex-9" class="collapse">
                    <td>
                        <pre>
                            modprobe ipv6
                            ip tunnel add netassist mode sit remote 62.205.132.12 local <?= long2ip($data_user->ip); ?> ttl 200
                            ip link set netassist up
                            ip addr add <?= $data['ipv6_if_their']; ?>/64 dev netassist
                            ip route add ::/0 dev netassist
                            ip -f inet6 addr
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-13'>
                    <td class="btn btn-primary btn-block btn-custom">MacOS X</td>
                </tr>
                <tr id="ex-13" class="collapse">
                    <td>
                        <pre>
                            ifconfig gif0 create
                            ifconfig gif0 tunnel <?= long2ip($data_user->ip); ?> 62.205.132.12
                            ifconfig gif0 inet6 <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> prefixlen 128
                            route -n add -inet6 default <?= $data['ipv6_if_our']."\n"; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-10'>
                    <td class="btn btn-primary btn-block btn-custom">Mikrotik</td>
                </tr>
                <tr id="ex-10" class="collapse">
                    <td>
                        <pre>
                            /interface 6to4 add comment="NetAssist IPv6 Tunnel Broker" disabled=no local-address=<?= long2ip($data_user->ip); ?> mtu=1280 name=sit1 remote-address=62.205.132.12
                            /ipv6 route add comment="" disabled=no distance=1 dst-address=2000::/3 gateway=<?= $data['ipv6_if_our']; ?> scope=30 target-scope=10
                            /ipv6 address add address=<?= $data['ipv6_if_their']; ?>/64 advertise=yes disabled=no eui-64=no interface=sit1

                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-11'>
                    <td class="btn btn-primary btn-block btn-custom">NetBSD</td>
                </tr>
                <tr id="ex-11" class="collapse">
                    <td>
                        <pre>
                            ifconfig gif0 create
                            ifconfig gif0 tunnel <?= long2ip($data_user->ip); ?> 62.205.132.12
                            ifconfig gif0 inet6 <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> prefixlen 128
                            route -n add -inet6 default <?= $data['ipv6_if_our']; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-12'>
                    <td class="btn btn-primary btn-block btn-custom">OpenBSD</td>
                </tr>
                <tr id="ex-12" class="collapse">
                    <td>
                        <pre>
                            ifconfig gif0 tunnel <?= long2ip($data_user->ip); ?> 62.205.132.12
                            ifconfig gif0 inet6 alias <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> prefixlen 128
                            route -n add -inet6 default <?= $data['ipv6_if_our']; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-14'>
                    <td class="btn btn-primary btn-block btn-custom">ScreenOS 6.2.0r1.0</td>
                </tr>
                <tr id="ex-14" class="collapse">
                    <td>
                        <pre>
    set interface tunnel.1 zone Untrust
    set interface tunnel.1 ipv6 mode host
    set interface tunnel.1 ipv6 ip <?= $data['ipv6_if_their']; ?>/64
    set interface tunnel.1 ipv6 enable
    set interface tunnel.1 tunnel encap ip6in4 manual
    set interface tunnel.1 tunnel local-if untrust dst-ip 62.205.132.12
    unset interface tunnel.1 ipv6 nd nud
    set interface tunnel.1 ipv6 nd dad-count 0
    set route ::/0 interface tunnel.1 gateway <?= $data['ipv6_if_our']; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-15'>
                    <td class="btn btn-primary btn-block btn-custom">Solaris</td>
                </tr>
                <tr id="ex-15" class="collapse">
                    <td>
                        <pre>
               ifconfig ip.tun0 inet6 plumb
               ifconfig ip.tun0 inet6 tsrc <?= long2ip($data_user->ip); ?> tdst 62.205.132.12 up
               ifconfig ip.tun0 inet6 addif <?= $data['ipv6_if_their']; ?> <?= $data['ipv6_if_our']; ?> up
               route add -inet6 default <?= $data['ipv6_if_our']; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-16'>
                    <td class="btn btn-primary btn-block btn-custom">Vyatta</td>
                </tr>
                <tr id="ex-16" class="collapse">
                    <td>
                        <pre>
configure
edit interfaces tunnel tun0
set encapsulation sit
set local-ip <?= long2ip($data_user->ip); ?> set remote-ip 62.205.132.12
set address <?= $data['ipv6_if_their']; ?> set description "NetAssist IPv6 Tunnel"
exit
set protocols static interface-route6 ::/0 next-hop-interface tun0
commit
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-17'>
                    <td class="btn btn-primary btn-block btn-custom">Windows 2000/XP</td>
                </tr>
                <tr id="ex-17" class="collapse">
                    <td>
                        <pre>
               ipv6 install
               ipv6 rtu ::/0 2/::62.205.132.12 pub
               ipv6 adu 2/ <?= $data['ipv6_if_their']; ?>
                        </pre>
                    </td>
                </tr>

                <tr data-toggle="collapse" data-target='#ex-18'>
                    <td class="btn btn-primary btn-block btn-custom">Windows 2000/XP</td>
                </tr>
                <tr id="ex-18" class="collapse">
                    <td>
                        <pre>
               netsh interface teredo set state disabled
               netsh interface ipv6 add v6v4tunnel IP6Tunnel <?= long2ip($data_user->ip); ?> 62.205.132.12
               netsh interface ipv6 add address IP6Tunnel <?= $data['ipv6_if_their']."\n"; ?>
               netsh interface ipv6 add route ::/0 IP6Tunnel <?= $data['ipv6_if_our']; ?>
                        </pre>
                    </td>
                </tr>


                </tbody>
            </table>

            <div class="alert alert-info" role="alert" >
                When behind a firewall appliance that passes protocol 41, use the IPv4 address you get from your
                appliance's DHCP service instead of the IPv4 endpoint you provided to our broker.<br>
                The configurations provided are example configurations and may be different depending on the version
                of the OS or the tools you are using.
            </div>





        </div>

    </div>


</div>

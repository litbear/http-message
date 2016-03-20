<?php
namespace Psr\Http\Message;

/**
 * Value object representing a URI.
 * 代表URI的对象接口
 *
 * This interface is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 * 本接口代表了RFC 3986文档中描述的URI，并且提供了最为通用的方法。
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @link http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
interface UriInterface
{
    /**
     * Retrieve the scheme component of the URI.
     * 获得URI的格式组件
     *
     * If no scheme is present, this method MUST return an empty string.
     * 假如当前没有提供格式，则方法 必须（MUST） 返回空字符串
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     * 本方法 必须（MUST） 返回一个小写的字符串， 根据闻到那股 RFC3986 3.1章
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     * 后最 ":" 字符不是个好似组件的一部分 一定不能（MUST NO）添加
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    public function getScheme();

    /**
     * Retrieve the authority component of the URI.
     * 获得URI的 认证组件
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     * 假如未提供认证信息，则方法 必须（MUST） 返回一个空字符串
     *
     * The authority syntax of the URI is:
     * 认证 的格式如下：
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     * 假如未设置端口组件，或者端口组件是当前格式的默认端口 则 不应（SHOULD NOT） 
     * 被包含
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority();

    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo();

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost();

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The URI port.
     */
    public function getPort();

    /**
     * Retrieve the path component of the URI.
     * 获得URI的路径组件
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     * 路径可以使空的 或者是 以斜线开头的绝对路径 或者是无根的（即不以斜线开头）
     * 的相对路径。本方法的实现 必须（MUST） 支持所有的三种形式
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     * 一般情况下  空路径"" 和绝对路径 "/" 根据（文档RFC 7230 2.7.3章）被认作是
     * 等价的，但是本方法 一定不要（MUST NOT） 自动的将其认标准化 因为URI的上下文
     * 语境中 基本路径是 两端去除 "/" 的，例如，前端控制器，这种差异是非常重要的
     * 用户的任务就是既处理"" 又处理"/"
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     * 返回值 必须（MUSY） 被URI编码。但是 一定不要（MUST NOT） 二次编码任何字符。
     * 要判断那些字符需要转码 请参考 RFC 3986 第2章和3.3章
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     * 举例来说，假如路径中的非分隔符中古代有斜线，则该值 必须（MUST） 被转义为
     * (e.g., "%2F")
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath();

    /**
     * Retrieve the query string of the URI.
     * 获取URI的查询字符串
     *
     * If no query string is present, this method MUST return an empty string.
     * 假如没有提供查询字符串，则方法 必须（MUST） 返回空字符串
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     * 开头的"?" 不是查询字符串的一部分，一定不要（MUST NOT） 添加上
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     * 返回值 必须（MUSY） 被URI编码。但是 一定不要（MUST NOT） 二次编码任何字符。
     * 要判断那些字符需要转码 请参考 RFC 3986 第2章和3.3章
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     * 例如，查询字符串中的键值对中如果包含一个非间隔负的"&"，则该值 必须（MUST）
     * 被转义为 %26 
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery();

    /**
     * Retrieve the fragment component of the URI.
     * 获取URI的段落组件
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment();

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return self A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme($scheme);

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return self A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null);

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return self A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host);

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return self A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port);

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return self A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path);

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return self A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query);

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return self A new instance with the specified fragment.
     */
    public function withFragment($fragment);

    /**
     * Return the string representation as a URI reference.
     * 返回URI的字符串形式
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     * 取决于URI组件的组件，字符串形式的URI可以使完整的，也可以使RFC 3986 4.1章
     * 定义的相对引用。本方法使用适当的连接符连接URI的各个组件
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - 假如提供的格式组件，则 必须（MUST） 提供 ":" 后缀
     * - If an authority is present, it MUST be prefixed by "//".
     * - 假如提供了认证组件，则必须以 "//" 为前缀
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     * - 路径可以不使用分隔符进行串联，但以下两种情形必须进行判断，因为PHP
     *   不允许在__toString()方法中抛出异常。
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - 假如路径无根，并且提供了认证组件，则路径 必须（MUST）以斜线"/" 为前缀
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     *     - 假如路径路径以多个"/" 为前缀，且未提供认证组件 则开头的斜线"/" 必须是一个
     * - If a query is present, it MUST be prefixed by "?".
     * - 假如提供了查询字符串前缀，则必须以（MUST） "?" 为前缀
     * - If a fragment is present, it MUST be prefixed by "#".
     * - 假如提供了段落组件，则 必须（MUST） 以"#" 为前缀
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString();
}

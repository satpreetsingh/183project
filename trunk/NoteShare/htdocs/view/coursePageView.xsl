<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="sessionMetadata">
	<h1><xsl:value-of select="@course"/></h1>fb namespace
	<h2><xsl:value-of select="@university"/></h2>
	<h2><xsl:value-of select="@department"/></h2>
	<p><xsl:value-of select="."/></p>
</xsl:template>

<xsl:template match="memberList">
	<div>
		<xsl:for-each select="member">
			<xsl:sort select="@friend" order="descending" />
			<div>
				<xsl:attribute name="class">
					<xsl:choose>
						<xsl:when test="@friend='True'">friend</xsl:when>
						<xsl:otherwise>notFriend</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<fb:profile-pic linked="true">
					<xsl:attribute name="uid"><xsl:value-of select="@id"/></xsl:attribute>
				</fb:profile-pic>
				</div>
		</xsl:for-each> 
	</div>
</xsl:template>

</xsl:stylesheet>
